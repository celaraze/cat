<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceForm;
use App\Filament\Forms\SecretForm;
use App\Filament\Resources\DeviceCategoryResource;
use App\Filament\Resources\TicketResource;
use App\Models\Device;
use App\Models\DeviceHasSecret;
use App\Models\Ticket;
use App\Services\AssetNumberRuleService;
use App\Services\DeviceService;
use App\Services\FlowHasFormService;
use App\Services\SettingService;
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
                    $setting_service = new SettingService();
                    $setting_service->set('device_retire_flow_id', $data['flow_id']);
                    NotificationUtil::make(true, __('cat/device.action.set_retire_flow_success'));
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
            });
    }

    public static function forceRetire(): Action
    {
        return Action::make(__('cat/device.action.force_retire'))
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
                    $device_retire_flow = $device->service()->getRetireFlow();
                    $asset_number = $device->getAttribute('asset_number');
                    $flow_has_form_service = new FlowHasFormService();
                    $data['flow_id'] = $device_retire_flow->getKey();
                    $data['name'] = __('cat/device.action.retire_flow_name').' - '.$asset_number;
                    $data['payload'] = $asset_number;
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat/device.action.retire_success'));
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

    public static function viewToken(): Action
    {
        return Action::make(__('cat/device.action.view_token'))
            ->icon('heroicon-m-key')
            ->color('warning')
            ->requiresConfirmation()
            ->modalDescription(__('cat/device.action.view_token_helper'))
            ->form(SecretForm::viewToken())
            ->action(function (array $data, DeviceHasSecret $device_has_secret) {
                try {
                    $secret = $device_has_secret->secret()->first();
                    if (auth()->attempt(['email' => auth()->user()->email, 'password' => $data['password']])) {
                        NotificationUtil::make(true, __('cat/secret.password').decrypt($secret->getAttribute('token')), true);
                    } else {
                        NotificationUtil::make(false, __('cat/device.action.view_token_failure'));
                    }
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
