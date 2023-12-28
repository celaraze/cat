<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceForm;
use App\Filament\Forms\SecretForm;
use App\Filament\Resources\DeviceCategoryResource;
use App\Filament\Resources\TicketResource;
use App\Models\Device;
use App\Models\DeviceHasSecret;
use App\Models\Flow;
use App\Models\Ticket;
use App\Services\AssetNumberRuleService;
use App\Services\DeviceService;
use App\Services\FlowHasFormService;
use App\Services\FlowHasNodeService;
use App\Services\FlowService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class DeviceAction
{
    public static function setRetireFlow(): Action
    {
        return Action::make(__('cat/device.action.set_retire_flow'))
            ->slideOver()
            ->form(DeviceForm::setRetireFlow())
            ->action(function (array $data) {
                try {
                    /* @var Flow $flow */
                    $flow = DeviceService::getRetireFlow();
                    if (! $flow) {
                        $flow_data['name'] = __('cat/device.action.retire_flow_name');
                        $flow_data['slug'] = 'retire_flow';
                        $flow_data['model_name'] = Device::class;
                        $flow_data['creator_id'] = 0;
                        $flow_service = new FlowService();
                        $flow = $flow_service->create($flow_data);
                    }
                    $data['flow_id'] = $flow->getKey();
                    $flow_has_node_service = new FlowHasNodeService();
                    $flow_has_node_service->batchCreate($data);
                    NotificationUtil::make(true, __('cat/device.action.set_retire_flow_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat/device.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(DeviceForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $device_service = new DeviceService();
                    $device_service->create($data);
                    NotificationUtil::make(true, __('cat/device.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setAssetNumberRule(): Action
    {
        return Action::make(__('cat/device.action.set_asset_number_rule'))
            ->slideOver()
            ->form(DeviceForm::setAssetNumberRule())
            ->action(function (array $data) {
                $data['class_name'] = Device::class;
                AssetNumberRuleService::setAutoRule($data);
                NotificationUtil::make(true, __('cat/device.action.set_asset_number_rule_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function resetAssetNumberRule(): Action
    {
        return Action::make(__('cat/device.action.reset_asset_number_rule'))
            ->slideOver()
            ->requiresConfirmation()
            ->action(function () {
                AssetNumberRuleService::resetAutoRule(Device::class);
                NotificationUtil::make(true, __('cat/device.action.reset_asset_number_rule_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function forceRetire(): Action
    {
        return Action::make(__('cat/device.action.force_retire'))
            ->slideOver()
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(DeviceForm::forceRetire())
            ->action(function (Device $device) {
                try {
                    $device->service()->retire();
                    NotificationUtil::make(true, __('cat/device.action.force_retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function retire(): Action
    {
        return Action::make(__('cat/device.action.retire'))
            ->slideOver()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(DeviceForm::retire())
            ->action(function (array $data, Device $device) {
                try {
                    /* @var Flow $flow */
                    $flow = DeviceService::getRetireFlow();
                    if (! $flow->nodes()->count()) {
                        throw new Exception('cat/device.action.retire_flow_not_set');
                    }
                    $data['flow_has_node_id'] = $flow->nodes()->where('order', 0)->first()->getKey();
                    $asset_number = $device->getAttribute('asset_number');
                    $data['name'] = __('cat/device.action.retire_flow_name').' - '.$asset_number;
                    $data['model_name'] = Device::class;
                    $data['model_id'] = $device->getKey();
                    $flow_has_form_service = new FlowHasFormService();
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat/device.action.retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toCategory(): Action
    {
        return Action::make(__('cat/menu.device_category'))
            ->icon('heroicon-s-square-3-stack-3d')
            ->url(DeviceCategoryResource::getUrl('index'));
    }

    public static function toTicket(): Action
    {
        return Action::make(__('cat/device.action.to_ticket'))
            ->icon('heroicon-o-document-text')
            ->url(function (Ticket $ticket) {
                return TicketResource::getUrl('view', ['record' => $ticket->getKey()]);
            });
    }

    public static function summary(): Action
    {
        return Action::make(__('cat/device.action.summary'))
            ->icon('heroicon-o-presentation-chart-bar')
            ->modalContent(function (Device $device) {
                return view('cat.actions.devices.summary', ['device' => $device]);
            })
            ->modalHeading(false)
            ->modalCancelAction(false)
            ->modalSubmitAction(false)
            ->link();
    }

    // TODO 这个方法后面看看怎么和 SecretAction 中的合并到一起
    public static function viewToken(): Action
    {
        return Action::make(__('cat/secret.action.view_token'))
            ->slideOver()
            ->icon('heroicon-m-key')
            ->color('warning')
            ->requiresConfirmation()
            ->modalDescription(__('cat/secret.action.view_token_helper'))
            ->form(SecretForm::viewToken())
            ->action(function (array $data, DeviceHasSecret $device_has_secret) {
                try {
                    $secret = $device_has_secret->secret()->first();
                    if (auth()->attempt(['email' => auth()->user()->email, 'password' => $data['password']])) {
                        NotificationUtil::make(true, __('cat/secret.password').decrypt($secret->getAttribute('token')), true);
                    } else {
                        NotificationUtil::make(false, __('cat/secret.action.view_token_failure'));
                    }
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
