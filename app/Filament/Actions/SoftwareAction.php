<?php

namespace App\Filament\Actions;

use App\Filament\Forms\SoftwareForm;
use App\Filament\Resources\SoftwareCategoryResource;
use App\Models\Software;
use App\Services\AssetNumberRuleService;
use App\Services\FlowHasFormService;
use App\Services\SettingService;
use App\Services\SoftwareService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class SoftwareAction
{
    public static function setRetireFlow(): Action
    {
        return Action::make(__('cat/software.action.set_retire_flow'))
            ->slideOver()
            ->form(SoftwareForm::setRetireFlow())
            ->action(function (array $data) {
                try {
                    $setting_service = new SettingService();
                    $setting_service->set('software_retire_flow_id', $data['flow_id']);
                    NotificationUtil::make(true, __('cat/software.action.set_retire_flow_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setAssetNumberRule(): Action
    {
        return Action::make(__('cat/software.action.set_asset_number_rule'))
            ->slideOver()
            ->form(SoftwareForm::setAssetNumberRule())
            ->action(function (array $data) {
                $data['class_name'] = Software::class;
                AssetNumberRuleService::setAutoRule($data);
                NotificationUtil::make(true, __('cat/software.action.set_asset_number_rule_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function resetAssetNumberRule(): Action
    {
        return Action::make(__('cat/software.action.reset_asset_number_rule'))
            ->slideOver()
            ->requiresConfirmation()
            ->action(function () {
                AssetNumberRuleService::resetAutoRule(Software::class);
                NotificationUtil::make(true, __('cat/software.action.reset_asset_number_rule_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function forceRetire(): Action
    {
        return Action::make(__('cat/software.action.force_retire'))
            ->slideOver()
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->action(function (Software $software) {
                try {
                    $software->service()->retire();
                    NotificationUtil::make(true, __('cat/software.action.force_retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function retire(): Action
    {
        return Action::make(__('cat/software.action.retire'))
            ->slideOver()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(SoftwareForm::retire())
            ->action(function (array $data, Software $software) {
                try {
                    $software_retire_flow = $software->service()->getRetireFlow();
                    $asset_number = $software->getAttribute('asset_number');
                    $flow_has_form_service = new FlowHasFormService();
                    $data['flow_id'] = $software_retire_flow->getKey();
                    $data['name'] = __('cat/software.action.retire_flow_name').' - '.$asset_number;
                    $data['payload'] = $asset_number;
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat/software.action.retire_flow_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat/software.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SoftwareForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $software_service = new SoftwareService();
                    $software_service->create($data);
                    NotificationUtil::make(true, __('cat/software.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toCategory(): Action
    {
        return Action::make(__('cat/menu.software_category'))
            ->icon('heroicon-s-square-3-stack-3d')
            ->url(SoftwareCategoryResource::getUrl('index'));
    }
}
