<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceForm;
use App\Filament\Forms\DeviceHasPartForm;
use App\Filament\Forms\DeviceHasSoftwareForm;
use App\Filament\Forms\DeviceHasUserForm;
use App\Filament\Resources\DeviceCategoryResource;
use App\Filament\Resources\TicketResource;
use App\Models\Device;
use App\Models\DeviceHasPart;
use App\Models\DeviceHasSoftware;
use App\Models\DeviceHasUser;
use App\Models\Ticket;
use App\Services\AssetNumberRuleService;
use App\Services\DeviceHasPartService;
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
    /**
     * 分配用户按钮.
     */
    public static function createHasUser(?Model $out_device = null): Action
    {
        return Action::make('分配用户')
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
                    NotificationUtil::make(true, '设备已分配用户');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 创建设备.
     */
    public static function create(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(DeviceForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $device_service = new DeviceService();
                    $device_service->create($data);
                    NotificationUtil::make(true, '已新增设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 解除用户按钮.
     */
    public static function deleteHasUser(?Model $out_device = null): Action
    {
        return Action::make('解除用户')
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
                    NotificationUtil::make(true, '设备已解除用户');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 附加软件按钮.
     */
    public static function createHasSoftware(?Model $out_device = null): Action
    {
        return Action::make('附加软件')
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
                    NotificationUtil::make(true, '设备已附加软件');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 创建设备配件按钮.
     */
    public static function createHasPart(?Model $out_device = null): Action
    {
        return Action::make('附加配件')
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
                    NotificationUtil::make(true, '设备已附加配件');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 配件脱离设备按钮.
     */
    public static function deleteHasPart(): Action
    {
        return Action::make('脱离')
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
                    NotificationUtil::make(true, '已脱离设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 软件脱离设备按钮.
     */
    public static function deleteHasSoftware(): Action
    {
        return Action::make('脱离')
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
                    NotificationUtil::make(true, '已脱离设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 配置设备报废流程.
     */
    public static function setRetireFlow(): Action
    {
        return Action::make('配置报废流程')
            ->slideOver()
            ->form(DeviceForm::setRetireFlow())
            ->action(function (array $data) {
                try {
                    $setting_service = new SettingService();
                    $setting_service->set('device_retire_flow_id', $data['flow_id']);
                    NotificationUtil::make(true, '流程配置成功');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 设置资产编号生成配置.
     */
    public static function setAssetNumberRule(): Action
    {
        return Action::make('配置资产编号自动生成规则')
            ->slideOver()
            ->form(DeviceForm::setAssetNumberRule())
            ->action(function (array $data) {
                $data['class_name'] = Device::class;
                AssetNumberRuleService::setAutoRule($data);
                NotificationUtil::make(true, '已配置资产编号自动生成规则');
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 重置资产编号生成配置.
     */
    public static function resetAssetNumberRule(): Action
    {
        return Action::make('清除资产编号自动生成规则')
            ->slideOver()
            ->requiresConfirmation()
            ->action(function () {
                AssetNumberRuleService::resetAutoRule(Device::class);
                NotificationUtil::make(true, '已清除编号自动生成规则');
            });
    }

    /**
     * 强制报废按钮.
     */
    public static function forceRetire(): Action
    {
        return Action::make('强制报废')
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(DeviceForm::forceRetire())
            ->action(function (Device $device) {
                try {
                    $device->service()->retire();
                    NotificationUtil::make(true, '已报废');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 流程报废按钮.
     */
    public static function retire(): Action
    {
        return Action::make('流程报废')
            ->slideOver()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(DeviceForm::retire())
            ->action(function (array $data, Device $device) {
                try {
                    $device_retire_flow = $device->service()->getRetireFlow();
                    $asset_number = $device->getAttribute('asset_number');
                    $flow_has_form_service = new FlowHasFormService();
                    $data['flow_id'] = $device_retire_flow->getKey();
                    $data['name'] = '设备报废单 - '.$asset_number;
                    $data['payload'] = $asset_number;
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, '已创建表单');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 前往设备分类清单.
     */
    public static function toCategories(): Action
    {
        return Action::make('分类')
            ->icon('heroicon-s-square-3-stack-3d')
            ->url(DeviceCategoryResource::getUrl('index'));
    }

    /**
     * 创建工单.
     *
     * @param  null  $asset_number
     */
    public static function createTicket($asset_number = null): Action
    {
        return Action::make('创建工单')
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
                    $ticket_service = new TicketService();
                    $ticket_service->create($data);
                    NotificationUtil::make(true, '已创建工单');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 前往工单.
     */
    public static function toTicket(): Action
    {
        return Action::make('前往')
            ->icon('heroicon-o-document-text')
            ->url(function (Ticket $ticket) {
                return TicketResource::getUrl('view', ['record' => $ticket->getKey()]);
            });
    }

    /**
     * 资产总览.
     */
    public static function summary(): Action
    {
        return Action::make('速览')
            ->icon('heroicon-o-presentation-chart-bar')
            ->modalContent(function (Device $device) {
                return view('filament.actions.devices.summary', ['device' => $device]);
            })
            ->modalHeading(false)
            ->modalCancelAction(false)
            ->modalSubmitAction(false)
            ->link();
    }

    /**
     * 批量脱离配件按钮.
     */
    public static function batchDeleteHasPart(): BulkAction
    {
        return BulkAction::make('批量脱离')
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
                NotificationUtil::make(true, '已批量脱离');
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 批量脱离软件按钮.
     */
    public static function batchDeleteHasSoftware(): BulkAction
    {
        return BulkAction::make('批量脱离')
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
                NotificationUtil::make(true, '已批量脱离');
            })
            ->closeModalByClickingAway(false);
    }
}
