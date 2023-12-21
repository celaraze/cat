<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceForm;
use App\Filament\Forms\DeviceHasPartForm;
use App\Filament\Forms\DeviceHasSecretForm;
use App\Filament\Forms\DeviceHasSoftwareForm;
use App\Filament\Forms\DeviceHasUserForm;
use App\Filament\Forms\SecretForm;
use App\Filament\Resources\DeviceCategoryResource;
use App\Filament\Resources\TicketResource;
use App\Models\Device;
use App\Models\DeviceHasPart;
use App\Models\DeviceHasSecret;
use App\Models\DeviceHasSoftware;
use App\Models\DeviceHasUser;
use App\Models\Ticket;
use App\Services\AssetNumberRuleService;
use App\Services\DeviceHasPartService;
use App\Services\DeviceHasSecretService;
use App\Services\DeviceHasSoftwareService;
use App\Services\DeviceHasUserService;
use App\Services\DeviceService;
use App\Services\FlowHasFormService;
use App\Services\SettingService;
use App\Services\TicketService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DeviceAction
{
    public static function createHasUser(?Model $out_device = null): Action
    {
        return Action::make(__('cat.action.assign_user'))
            ->slideOver()
            ->icon('heroicon-s-user-plus')
            ->form(DeviceHasUserForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    $data['device_id'] = $device->getKey();
                    $data['creator_id'] = auth()->id();
                    $device_has_user_service = new DeviceHasUserService();
                    $device_has_user_service->create($data);
                    NotificationUtil::make(true, __('cat.action.assign_user_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(DeviceForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $device_service = new DeviceService();
                    $device_service->create($data);
                    NotificationUtil::make(true, __('cat.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteHasUser(?Model $out_device = null): Action
    {
        return Action::make(__('cat.action.unassign_user'))
            ->slideOver()
            ->icon('heroicon-s-user-minus')
            ->form(DeviceHasUserForm::delete())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    $data['device_id'] = $device->getKey();
                    /* @var DeviceHasUser $device_has_user */
                    $device_has_user = $device->hasUsers()->first();
                    $device_has_user_service = $device_has_user->service();
                    $device_has_user_service->delete($data);
                    NotificationUtil::make(true, __('cat.action.unassign_user_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function createHasSoftware(?Model $out_device = null): Action
    {
        return Action::make(__('cat.action.attach_software'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasSoftwareForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    foreach ($data['software_ids'] as $software_id) {
                        $data['software_id'] = $software_id;
                        $data['device_id'] = $device->getKey();
                        $data['creator_id'] = auth()->id();
                        $data['status'] = 0;
                        $device_has_software_service = new DeviceHasSoftwareService();
                        $device_has_software_service->create($data);
                    }
                    NotificationUtil::make(true, __('cat.action.attach_software_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function createHasPart(?Model $out_device = null): Action
    {
        return Action::make(__('cat.action.attach_part'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasPartForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    foreach ($data['part_ids'] as $part_id) {
                        $data['part_id'] = $part_id;
                        $data['device_id'] = $device->getKey();
                        $data['creator_id'] = auth()->id();
                        $data['status'] = 0;
                        $device_has_part_service = new DeviceHasPartService();
                        $device_has_part_service->create($data);
                    }
                    NotificationUtil::make(true, __('cat.action.attach_part_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function createHasSecret(?Model $out_device = null): Action
    {
        return Action::make(__('cat.action.attach_secret'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasSecretForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    foreach ($data['secret_ids'] as $secret_id) {
                        $data['secret_id'] = $secret_id;
                        $data['device_id'] = $device->getKey();
                        $data['creator_id'] = auth()->id();
                        $data['status'] = 0;
                        $device_has_secret_service = new DeviceHasSecretService();
                        $device_has_secret_service->create($data);
                    }
                    NotificationUtil::make(true, __('cat.action.attach_secret_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteHasPart(): Action
    {
        return Action::make(__('cat.action.detach_part'))
            ->icon('heroicon-s-minus-circle')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasPart $device_has_part) {
                try {
                    $data = [
                        'creator_id' => auth()->id(),
                        'status' => 1,
                    ];
                    $device_has_part->service()->delete($data);
                    NotificationUtil::make(true, __('cat.action.detach_part_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteHasSecret(): Action
    {
        return Action::make(__('cat.action.detach_secret'))
            ->icon('heroicon-s-minus-circle')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasSecret $device_has_secret) {
                try {
                    $data = [
                        'creator_id' => auth()->id(),
                        'status' => 1,
                    ];
                    $device_has_secret->service()->delete($data);
                    NotificationUtil::make(true, __('cat.action.detach_secret_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteHasSoftware(): Action
    {
        return Action::make(__('cat.action.detach_software'))
            ->icon('heroicon-s-minus-circle')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasSoftware $device_has_software) {
                try {
                    $data = [
                        'creator_id' => auth()->id(),
                        'status' => 1,
                    ];
                    $device_has_software->service()->delete($data);
                    NotificationUtil::make(true, __('cat.action.detach_software_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setRetireFlow(): Action
    {
        return Action::make(__('cat.action.set_retire_flow'))
            ->slideOver()
            ->form(DeviceForm::setRetireFlow())
            ->action(function (array $data) {
                try {
                    $setting_service = new SettingService();
                    $setting_service->set('device_retire_flow_id', $data['flow_id']);
                    NotificationUtil::make(true, __('cat.action.set_retire_flow_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setAssetNumberRule(): Action
    {
        return Action::make(__('cat.action.set_asset_number_rule'))
            ->slideOver()
            ->form(DeviceForm::setAssetNumberRule())
            ->action(function (array $data) {
                $data['class_name'] = Device::class;
                AssetNumberRuleService::setAutoRule($data);
                NotificationUtil::make(true, __('cat.action.set_asset_number_rule_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function resetAssetNumberRule(): Action
    {
        return Action::make(__('cat.action.reset_asset_number_rule'))
            ->slideOver()
            ->requiresConfirmation()
            ->action(function () {
                AssetNumberRuleService::resetAutoRule(Device::class);
                NotificationUtil::make(true, __('cat.action.reset_asset_number_rule_success'));
            });
    }

    public static function forceRetire(): Action
    {
        return Action::make(__('cat.action.force_retire'))
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(DeviceForm::forceRetire())
            ->action(function (Device $device) {
                try {
                    $device->service()->retire();
                    NotificationUtil::make(true, __('cat.action.force_retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function retire(): Action
    {
        return Action::make(__('cat.action.retire'))
            ->slideOver()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(DeviceForm::retire())
            ->action(function (array $data, Device $device) {
                try {
                    $device_retire_flow = $device->service()->getRetireFlow();
                    $asset_number = $device->getAttribute('asset_number');
                    $flow_has_form_service = new FlowHasFormService();
                    $data['flow_id'] = $device_retire_flow->getKey();
                    $data['name'] = __('cat.action.retire_flow_name').' - '.$asset_number;
                    $data['payload'] = $asset_number;
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat.action.retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toCategory(): Action
    {
        return Action::make(__('cat.action.to_category'))
            ->icon('heroicon-s-square-3-stack-3d')
            ->url(DeviceCategoryResource::getUrl('index'));
    }

    public static function createTicket($asset_number = null): Action
    {
        return Action::make(__('cat.action.create_ticket'))
            ->icon('heroicon-m-plus-circle')
            ->slideOver()
            ->form(function (Device $device) use ($asset_number) {
                if (! $asset_number) {
                    $asset_number = $device->getAttribute('asset_number');
                }

                return DeviceForm::createTicketFromDevice($asset_number);
            })
            ->action(function (array $data, Device $device) use ($asset_number) {
                try {
                    if (! $asset_number) {
                        $asset_number = $device->getAttribute('asset_number');
                    }
                    $data['asset_number'] = $asset_number;
                    $data['user_id'] = auth()->id();
                    $ticket_service = new TicketService();
                    $ticket_service->create($data);
                    NotificationUtil::make(true, __('cat.action.created_ticket_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toTicket(): Action
    {
        return Action::make(__('cat.action.to_ticket'))
            ->icon('heroicon-o-document-text')
            ->url(function (Ticket $ticket) {
                return TicketResource::getUrl('view', ['record' => $ticket->getKey()]);
            });
    }

    public static function summary(): Action
    {
        return Action::make(__('cat.action.summary'))
            ->icon('heroicon-o-presentation-chart-bar')
            ->modalContent(function (Device $device) {
                return view('filament.actions.devices.summary', ['device' => $device]);
            })
            ->modalHeading(false)
            ->modalCancelAction(false)
            ->modalSubmitAction(false)
            ->link();
    }

    public static function batchDeleteHasPart(): BulkAction
    {
        return BulkAction::make(__('cat.action.batch_detach'))
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-s-minus-circle')
            ->action(function (Collection $device_has_parts) {
                $data = [
                    'creator_id' => auth()->id(),
                    'status' => 1,
                ];
                /* @var DeviceHasPart $device_has_part */
                foreach ($device_has_parts as $device_has_part) {
                    $device_has_part->service()->delete($data);
                }
                NotificationUtil::make(true, __('cat.action.batch_detach_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function batchDeleteHasSecret(): BulkAction
    {
        return BulkAction::make(__('cat.action.batch_detach'))
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-s-minus-circle')
            ->action(function (Collection $device_has_secrets) {
                $data = [
                    'creator_id' => auth()->id(),
                    'status' => 1,
                ];
                /* @var DeviceHasSecret $device_has_secret */
                foreach ($device_has_secrets as $device_has_secret) {
                    $device_has_secret->service()->delete($data);
                }
                NotificationUtil::make(true, __('cat.action.batch_detach_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function batchDeleteHasSoftware(): BulkAction
    {
        return BulkAction::make(__('cat.action.batch_detach'))
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-s-minus-circle')
            ->action(function (Collection $device_has_software) {
                $data = [
                    'creator_id' => auth()->id(),
                    'status' => 1,
                ];
                /* @var DeviceHasSoftware $item */
                foreach ($device_has_software as $item) {
                    $item->service()->delete($data);
                }
                NotificationUtil::make(true, __('cat.action.batch_detach_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function viewToken(): Action
    {
        return Action::make(__('cat.action.view_token'))
            ->icon('heroicon-m-key')
            ->color('warning')
            ->requiresConfirmation()
            ->modalDescription(__('cat.action.view_token_helper'))
            ->form(SecretForm::viewToken())
            ->action(function (array $data, DeviceHasSecret $device_has_secret) {
                try {
                    $secret = $device_has_secret->secret()->first();
                    if (auth()->attempt(['email' => auth()->user()->email, 'password' => $data['password']])) {
                        NotificationUtil::make(true, __('cat.password').decrypt($secret->getAttribute('token')), true);
                    } else {
                        NotificationUtil::make(false, __('cat.action.view_token_failure'));
                    }
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
