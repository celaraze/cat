<?php

namespace App\Filament\Actions;

use App\Filament\Forms\SoftwareForm;
use App\Models\DeviceHasSoftware;
use App\Models\Software;
use App\Services\AssetNumberRuleService;
use App\Services\DeviceService;
use App\Services\FlowService;
use App\Services\SettingService;
use App\Services\SoftwareCategoryService;
use App\Services\SoftwareService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class SoftwareAction
{
    /**
     * 创建配件.
     */
    public static function createSoftware(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SoftwareForm::createOrEditSoftware())
            ->action(function (array $data) {
                try {
                    $software_service = new SoftwareService();
                    $software_service->create($data);
                    NotificationUtil::make(true, '已新增软件');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建软件分类按钮.
     */
    public static function createSoftwareCategory(): Action
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
                    $software_category_service = new SoftwareCategoryService();
                    $software_category_service->create($data);
                    NotificationUtil::make(true, '已创建软件分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建软件-设备按钮.
     */
    public static function createDeviceHasSoftware(Model $out_software = null): Action
    {
        return Action::make('附加到设备')
            ->form([
                Select::make('device_id')
                    ->options(DeviceService::pluckOptions())
                    ->searchable()
                    ->label('设备'),
            ])
            ->action(function (array $data, Software $software) use ($out_software) {
                try {
                    if ($out_software) {
                        $software = $out_software;
                    }
                    $data = [
                        'device_id' => $data['device_id'],
                        'user_id' => auth()->id(),
                        'status' => '附加',
                    ];
                    $software->service()->createHasSoftware($data);
                    NotificationUtil::make(true, '软件已附加到设备');
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
                    NotificationUtil::make(true, '软件已脱离设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 绑定软件报废流程.
     */
    public static function setSoftwareRetireFlowId(): Action
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
                    $setting_service->set('software_retire_flow_id', $data['flow_id']);
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
                    ->default(AssetNumberRuleService::getAutoRule(Software::class)?->getAttribute('id')),
                Checkbox::make('is_auto')
                    ->label('自动生成')
                    ->default(AssetNumberRuleService::getAutoRule(Software::class)?->getAttribute('is_auto')),
            ])
            ->action(function (array $data) {
                $data['class_name'] = Software::class;
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
                AssetNumberRuleService::resetAutoRule(Software::class);
                NotificationUtil::make(true, '已清除编号自动生成规则');
            });
    }

    /**
     * 发起软件报废流程表单.
     */
    public static function retireSoftware(): Action
    {
        return Action::make('流程报废')
            ->form([
                TextInput::make('comment')
                    ->label('说明')
                    ->required(),
            ])
            ->action(function (array $data, Software $software) {
                try {
                    $software_retire_flow = $software->service()->getRetireFlow();
                    $flow_service = new FlowService($software_retire_flow);
                    $asset_number = $software->getAttribute('asset_number');
                    $flow_service->createHasForm(
                        '软件报废单',
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
    public static function forceRetireSoftware(): Action
    {
        return Action::make('强制报废')
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->action(function (array $data, Software $software) {
                try {
                    $software->service()->retire();
                    NotificationUtil::make(true, '已报废');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
