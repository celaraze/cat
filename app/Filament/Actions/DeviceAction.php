<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceCategoryForm;
use App\Filament\Forms\DeviceForm;
use App\Filament\Forms\DeviceHasPartForm;
use App\Filament\Forms\DeviceHasUserForm;
use App\Filament\Resources\TicketResource;
use App\Models\Device;
use App\Models\DeviceHasPart;
use App\Models\DeviceHasSoftware;
use App\Models\Ticket;
use App\Services\AssetNumberRuleService;
use App\Services\DeviceCategoryService;
use App\Services\DeviceService;
use App\Services\FlowService;
use App\Services\SettingService;
use App\Services\TicketService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class DeviceAction
{
    /**
     * 分配管理者按钮.
     */
    public static function createDeviceHasUser(Model $out_device = null): Action
    {
        return Action::make('分配管理者')
            ->icon('heroicon-s-user-plus')
            ->form(DeviceHasUserForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    $data = [
                        'user_id' => $data['user_id'],
                        'comment' => $data['comment'],
                    ];
                    $device->service()->createHasUser($data);
                    NotificationUtil::make(true, '设备已归属管理者');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 解除管理者按钮.
     */
    public static function deleteDeviceHasUser(Model $out_device = null): Action
    {
        return Action::make('解除管理者')
            ->icon('heroicon-s-user-minus')
            ->form(DeviceHasUserForm::delete())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    $device->service()->deleteHasUser($data);
                    NotificationUtil::make(true, '设备已解除管理者');
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
    public static function createDevice(): Action
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
     * 创建设备分类.
     */
    public static function createDeviceCategory(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(DeviceCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $device_category_service = new DeviceCategoryService();
                    $device_category_service->create($data);
                    NotificationUtil::make(true, '已创建设备分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 附加配件按钮.
     */
    public static function createDeviceHasSoftware(Model $out_device = null): Action
    {
        return Action::make('附加软件')
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasPartForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    $data = [
                        'software_id' => $data['software_id'],
                        'user_id' => auth()->id(),
                        'status' => '附加',
                    ];
                    $device->service()->createHasSoftware($data);
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
    public static function createDeviceHasPart(Model $out_device = null): Action
    {
        return Action::make('附加配件')
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasPartForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    $data = [
                        'part_id' => $data['part_id'],
                        'user_id' => auth()->id(),
                        'status' => '附加',
                    ];
                    $device->service()->createHasPart($data);
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
    public static function deleteDeviceHasPart(): Action
    {
        return Action::make('脱离')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasPart $device_has_part) {
                try {
                    $data = [
                        'user_id' => auth()->id(),
                        'status' => '脱离',
                    ];
                    $device_has_part->service()->delete($data);
                    NotificationUtil::make(true, '已脱离设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 软件脱离设备按钮.
     */
    public static function deleteDeviceHasSoftware(): Action
    {
        return Action::make('脱离')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasSoftware $device_has_software) {
                try {
                    $data = [
                        'user_id' => auth()->id(),
                        'status' => '脱离',
                    ];
                    $device_has_software->service()->delete($data);
                    NotificationUtil::make(true, '已脱离设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 配置设备报废流程.
     */
    public static function setDeviceRetireFlow(): Action
    {
        return Action::make('配置报废流程')
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
            ->requiresConfirmation()
            ->action(function () {
                AssetNumberRuleService::resetAutoRule(Device::class);
                NotificationUtil::make(true, '已清除编号自动生成规则');
            });
    }

    /**
     * 流程报废按钮.
     */
    public static function retireDevice(): Action
    {
        return Action::make('流程报废')
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(DeviceForm::retire())
            ->action(function (array $data, Device $device) {
                try {
                    $device_retire_flow = $device->service()->getRetireFlow();
                    $flow_service = new FlowService($device_retire_flow);
                    $asset_number = $device->getAttribute('asset_number');
                    $flow_service->createHasForm(
                        '设备报废单',
                        $asset_number.' 报废处理',
                        $asset_number
                    );
                    NotificationUtil::make(true, '已创建表单');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })->closeModalByClickingAway(false);
    }

    /**
     * 强制报废按钮.
     */
    public static function forceRetireDevice(): Action
    {
        return Action::make('强制报废')
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(DeviceForm::forceRetire())
            ->action(function (array $data, Device $device) {
                try {
                    $device->service()->retire();
                    NotificationUtil::make(true, '已报废');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 前往设备分类.
     */
    public static function toDeviceCategory(): Action
    {
        return Action::make('分类')
            ->icon('heroicon-s-square-3-stack-3d')
            ->url('/device-categories');
    }

    /**
     * 前往设备.
     */
    public static function toDevice(): Action
    {
        return Action::make('返回设备')
            ->icon('heroicon-s-server')
            ->url('/devices');
    }

    /**
     * 创建工单.
     *
     * @param  null  $asset_number
     */
    public static function createTicket($asset_number = null): Action
    {
        return Action::make('创建工单')
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
}
