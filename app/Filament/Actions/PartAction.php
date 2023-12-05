<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceHasPartForm;
use App\Filament\Forms\PartCategoryForm;
use App\Filament\Forms\PartForm;
use App\Models\DeviceHasPart;
use App\Models\Part;
use App\Services\AssetNumberRuleService;
use App\Services\FlowService;
use App\Services\PartCategoryService;
use App\Services\PartService;
use App\Services\SettingService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class PartAction
{
    /**
     * 创建设备分类按钮.
     */
    public static function createPartCategory(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(PartCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $part_category_service = new PartCategoryService();
                    $part_category_service->create($data);
                    NotificationUtil::make(true, '已创建配件分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建配件.
     */
    public static function createPart(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(PartForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $device_service = new PartService();
                    $device_service->create($data);
                    NotificationUtil::make(true, '已新增配件');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建配件-设备按钮.
     */
    public static function createDeviceHasPart(Model $out_part = null): Action
    {
        return Action::make('附加到设备')
            ->form(DeviceHasPartForm::createFromPart())
            ->action(function (array $data, Part $part) use ($out_part) {
                try {
                    if ($out_part) {
                        $part = $out_part;
                    }
                    $data = [
                        'device_id' => $data['device_id'],
                        'user_id' => auth()->id(),
                        'status' => '附加',
                    ];
                    $part->service()->createHasPart($data);
                    NotificationUtil::make(true, '配件已附加到设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
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
                    NotificationUtil::make(true, '配件已脱离设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 绑定配件报废流程.
     */
    public static function setPartRetireFlow(): Action
    {
        return Action::make('配置报废流程')
            ->form(PartForm::setRetireFlow())
            ->action(function (array $data) {
                try {
                    $setting_service = new SettingService();
                    $setting_service->set('part_retire_flow_id', $data['flow_id']);
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
            ->form(PartForm::setAssetNumberRule())
            ->action(function (array $data) {
                $data['class_name'] = Part::class;
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
                AssetNumberRuleService::resetAutoRule(Part::class);
                NotificationUtil::make(true, '已清除编号自动生成规则');
            });
    }

    /**
     * 发起配件报废流程表单.
     */
    public static function retirePart(): Action
    {
        return Action::make('流程报废')
            ->form(PartForm::retire())
            ->action(function (array $data, Part $part) {
                try {
                    $part_retire_flow = $part->service()->getRetireFlow();
                    $flow_service = new FlowService($part_retire_flow);
                    $asset_number = $part->getAttribute('asset_number');
                    $flow_service->createHasForm(
                        '配件报废单',
                        $data['comment'],
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
    public static function forceRetirePart(): Action
    {
        return Action::make('强制报废')
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->action(function (Part $part) {
                try {
                    $part->service()->retire();
                    NotificationUtil::make(true, '已报废');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 前往配件分类.
     */
    public static function toPartCategory(): Action
    {
        return Action::make('分类')
            ->icon('heroicon-s-square-3-stack-3d')
            ->url('/part-categories');
    }

    /**
     * 前往配件.
     */
    public static function toPart(): Action
    {
        return Action::make('返回配件')
            ->icon('heroicon-s-server')
            ->url('/parts');
    }
}
