<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceForm;
use App\Models\Device;
use App\Models\DeviceHasPart;
use App\Models\DeviceHasSoftware;
use App\Services\AssetNumberRuleService;
use App\Services\DeviceCategoryService;
use App\Services\DeviceService;
use App\Services\FlowService;
use App\Services\PartService;
use App\Services\SettingService;
use App\Services\SoftwareService;
use App\Services\UserService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Awcodes\Shout\Components\Shout;
use Exception;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
            ->form([
                //region 选择 管理者 user_id
                Select::make('user_id')
                    ->label('管理者')
                    ->options(UserService::pluckOptions())
                    ->searchable()
                    ->required(),
                //endregion

                //region 文本 说明 comment
                TextInput::make('comment')
                    ->label('说明')
                    ->required(),
                //endregion
            ])
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
            ->icon('heroicon-s-user-plus');
    }

    /**
     * 解除管理者按钮.
     */
    public static function deleteDeviceHasUser(Model $out_device = null): Action
    {
        return Action::make('解除管理者')
            ->form([
                //region 文本 解除说明 delete_comment
                TextInput::make('delete_comment')
                    ->label('解除说明')
                    ->required(),
                //endregion
            ])
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
            ->icon('heroicon-s-user-minus');
    }

    /**
     * 创建设备.
     */
    public static function createDevice(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(DeviceForm::createOrEditDevice())
            ->action(function (array $data) {
                try {
                    $device_service = new DeviceService();
                    $device_service->create($data);
                    NotificationUtil::make(true, '已新增设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建设备分类.
     */
    public static function createDeviceCategory(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form([
                TextInput::make('name')
                    ->label('名称')
                    ->required(),
            ])
            ->action(function (array $data) {
                try {
                    $device_category_service = new DeviceCategoryService();
                    $device_category_service->create($data);
                    NotificationUtil::make(true, '已创建设备分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建设备配件按钮.
     */
    public static function createDeviceHasPart(Model $out_device = null): Action
    {
        return Action::make('附加配件')
            ->form([
                //region 选择 配件 part_id
                Select::make('part_id')
                    ->label('配件')
                    ->options(PartService::pluckOptions())
                    ->searchable()
                    ->required(),
                //endregion
            ])
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
            ->icon('heroicon-m-plus-circle');
    }

    /**
     * 创建设备配件按钮.
     */
    public static function createDeviceHasSoftware(Model $out_device = null): Action
    {
        return Action::make('附加软件')
            ->form([
                //region 选择 软件 software_id
                Select::make('software_id')
                    ->label('配件')
                    ->options(SoftwareService::pluckOptions())
                    ->searchable()
                    ->required(),
                //endregion
            ])
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
            ->icon('heroicon-m-plus-circle');
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
     * 绑定设备报废流程.
     */
    public static function setDeviceRetireFlow(): Action
    {
        return Action::make('配置报废流程')
            ->form([
                Select::make('flow_id')
                    ->options(FlowService::pluckOptions())
                    ->required()
                    ->label('流程'),
            ])
            ->action(function (array $data) {
                try {
                    $setting_service = new SettingService();
                    $setting_service->set('device_retire_flow_id', $data['flow_id']);
                    NotificationUtil::make(true, '流程配置成功');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 设置资产编号生成配置.
     */
    public static function setAssetNumberRule(): Action
    {
        return Action::make('配置资产编号自动生成规则')
            ->form([
                Select::make('asset_number_rule_id')
                    ->label('规则')
                    ->options(AssetNumberRuleService::pluckOptions())
                    ->required()
                    ->default(AssetNumberRuleService::getAutoRule(Device::class)?->getAttribute('id')),
                Checkbox::make('is_auto')
                    ->label('自动生成')
                    ->default(AssetNumberRuleService::getAutoRule(Device::class)?->getAttribute('is_auto')),
            ])
            ->action(function (array $data) {
                $data['class_name'] = Device::class;
                AssetNumberRuleService::setAutoRule($data);
                NotificationUtil::make(true, '已配置资产编号自动生成规则');
            });
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
     * 发起设备报废流程表单.
     */
    public static function retireDevice(): Action
    {
        return Action::make('流程报废')
            ->icon('heroicon-m-archive-box-x-mark')
            ->form([
                Shout::make('')
                    ->color('danger')
                    ->content('此操作将同时报废所含配件（不包含软件）'),
                TextInput::make('comment')
                    ->label('说明')
                    ->required(),
            ])
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
            });
    }

    /**
     * 强制报废按钮.
     */
    public static function forceRetireDevice(): Action
    {
        return Action::make('强制报废')
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form([
                Shout::make('hint')
                    ->color('danger')
                    ->content('此操作将同时报废所含配件（不包含软件）'),
            ])
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
}
