<?php

namespace App\Filament\Actions;

use App\Filament\Forms\PartForm;
use App\Filament\Resources\PartCategoryResource;
use App\Models\Flow;
use App\Models\Part;
use App\Services\AssetNumberRuleService;
use App\Services\FlowHasFormService;
use App\Services\FlowHasNodeService;
use App\Services\FlowService;
use App\Services\PartService;
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
                    /* @var Flow $flow */
                    $flow = PartService::getRetireFlow();
                    if (! $flow) {
                        $flow_data['name'] = __('cat/part.action.retire_flow_name');
                        $flow_data['slug'] = 'retire_flow';
                        $flow_data['model_name'] = Part::class;
                        $flow_data['creator_id'] = 0;
                        $flow_service = new FlowService();
                        $flow = $flow_service->create($flow_data);
                    }
                    $data['flow_id'] = $flow->getKey();
                    $flow_has_node_service = new FlowHasNodeService();
                    $flow_has_node_service->batchCreate($data);
                    NotificationUtil::make(true, __('cat/part.action.set_retire_flow_success'));
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
                    /* @var Flow $flow */
                    $flow = PartService::getRetireFlow();
                    if (! $flow->nodes()->count()) {
                        throw new Exception('cat/part.action.retire_flow_not_set');
                    }
                    $data['flow_has_node_id'] = $flow->nodes()->where('order', 0)->first()->getKey();
                    $asset_number = $part->getAttribute('asset_number');
                    $data['name'] = __('cat/part.action.retire_flow_name').' - '.$asset_number;
                    $data['model_name'] = Part::class;
                    $data['model_id'] = $part->getKey();
                    $flow_has_form_service = new FlowHasFormService();
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat/part.action.retire_success'));
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
