<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceHasSoftwareForm;
use App\Filament\Forms\SoftwareCategoryForm;
use App\Filament\Forms\SoftwareForm;
use App\Models\DeviceHasSoftware;
use App\Models\Software;
use App\Services\AssetNumberRuleService;
use App\Services\FlowService;
use App\Services\SettingService;
use App\Services\SoftwareCategoryService;
use App\Services\SoftwareService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class SoftwareAction
{
    /**
     * 创建软件.
     */
    public static function createSoftware(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SoftwareForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $software_service = new SoftwareService();
                    $software_service->create($data);
                    NotificationUtil::make(true, '已新增软件');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 创建软件分类按钮.
     */
    public static function createSoftwareCategory(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SoftwareCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $software_category_service = new SoftwareCategoryService();
                    $software_category_service->create($data);
                    NotificationUtil::make(true, '已创建软件分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 软件附加到设备按钮.
     */
    public static function createDeviceHasSoftware(?Model $out_software = null): Action
    {
        return Action::make('附加到设备')
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasSoftwareForm::createFromSoftware())
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
            })
            ->closeModalByClickingAway(false);
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
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 配置软件报废流程.
     */
    public static function setSoftwareRetireFlow(): Action
    {
        return Action::make('配置报废流程')
            ->slideOver()
            ->form(SoftwareForm::setRetireFlow())
            ->action(function (array $data) {
                try {
                    $setting_service = new SettingService();
                    $setting_service->set('software_retire_flow_id', $data['flow_id']);
                    NotificationUtil::make(true, '流程配置成功');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 配置资产编号生成配置.
     */
    public static function setAssetNumberRule(): Action
    {
        return Action::make('配置资产编号自动生成规则')
            ->slideOver()
            ->form(SoftwareForm::setAssetNumberRule())
            ->action(function (array $data) {
                $data['class_name'] = Software::class;
                AssetNumberRuleService::setAutoRule($data);
                NotificationUtil::make(true, '已配置资产编号自动生成规则');
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 配置资产编号生成配置.
     */
    public static function resetAssetNumberRule(): Action
    {
        return Action::make('清除资产编号自动生成规则')
            ->requiresConfirmation()
            ->action(function () {
                AssetNumberRuleService::resetAutoRule(Software::class);
                NotificationUtil::make(true, '已清除编号自动生成规则');
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 流程报废按钮.
     */
    public static function retireSoftware(): Action
    {
        return Action::make('流程报废')
            ->slideOver()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(SoftwareForm::retire())
            ->action(function (array $data, Software $software) {
                try {
                    $software_retire_flow = $software->service()->getRetireFlow();
                    $flow_service = new FlowService($software_retire_flow);
                    $asset_number = $software->getAttribute('asset_number');
                    $flow_service->createHasForm(
                        '软件报废单',
                        $data['comment'],
                        $asset_number
                    );
                    NotificationUtil::make(true, '已创建表单');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 强制报废按钮.
     */
    public static function forceRetireSoftware(): Action
    {
        return Action::make('强制报废')
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->action(function (Software $software) {
                try {
                    $software->service()->retire();
                    NotificationUtil::make(true, '已报废');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 前往软件分类.
     */
    public static function toSoftwareCategory(): Action
    {
        return Action::make('分类')
            ->icon('heroicon-s-square-3-stack-3d')
            ->url('/software-categories');
    }

    /**
     * 前往软件.
     */
    public static function toSoftware(): Action
    {
        return Action::make('返回软件')
            ->icon('heroicon-s-server')
            ->url('/software');
    }
}
