<?php

namespace App\Filament\Actions;

use App\Filament\Forms\PartForm;
use App\Filament\Resources\PartCategoryResource;
use App\Models\Part;
use App\Services\AssetNumberRuleService;
use App\Services\FlowHasFormService;
use App\Services\PartService;
use App\Services\SettingService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class PartAction
{
    public static function setRetireFlow(): Action
    {
        return Action::make(__('cat/part.action.set_retire_flow'))
            ->slideOver()
            ->form(PartForm::setRetireFlow())
            ->action(function (array $data) {
                try {
                    $setting_service = new SettingService();
                    $setting_service->set('part_retire_flow_id', $data['flow_id']);
                    NotificationUtil::make(true, __('cat/part.action.set_retire_flow_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setAssetNumberRule(): Action
    {
        return Action::make(__('cat/part.action.set_asset_number_rule'))
            ->slideOver()
            ->form(PartForm::setAssetNumberRule())
            ->action(function (array $data) {
                $data['class_name'] = Part::class;
                AssetNumberRuleService::setAutoRule($data);
                NotificationUtil::make(true, __('cat/part.action.set_asset_number_rule_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function resetAssetNumberRule(): Action
    {
        return Action::make(__('cat/part.action.reset_asset_number_rule'))
            ->slideOver()
            ->requiresConfirmation()
            ->action(function () {
                AssetNumberRuleService::resetAutoRule(Part::class);
                NotificationUtil::make(true, __('cat/part.action.reset_asset_number_rule_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function forceRetire(): Action
    {
        return Action::make(__('cat/part.action.force_retire'))
            ->slideOver()
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->action(function (Part $part) {
                try {
                    $part->service()->retire();
                    NotificationUtil::make(true, __('cat/part.action.force_retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function retire(): Action
    {
        return Action::make(__('cat/part.action.retire'))
            ->slideOver()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(PartForm::retire())
            ->action(function (array $data, Part $part) {
                try {
                    $part_retire_flow = $part->service()->getRetireFlow();
                    $asset_number = $part->getAttribute('asset_number');
                    $flow_has_form_service = new FlowHasFormService();
                    $data['flow_id'] = $part_retire_flow->getKey();
                    $data['name'] = __('cat/part.action.retire_flow_name').' - '.$asset_number;
                    $data['payload'] = $asset_number;
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat/part.action.retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat/part.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(PartForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $device_service = new PartService();
                    $device_service->create($data);
                    NotificationUtil::make(true, __('cat/part.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toCategory(): Action
    {
        return Action::make(__('cat/menu.part_category'))
            ->icon('heroicon-s-square-3-stack-3d')
            ->url(PartCategoryResource::getUrl('index'));
    }
}
