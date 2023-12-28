<?php

namespace App\Filament\Actions;

use App\Filament\Forms\SoftwareForm;
use App\Filament\Resources\SoftwareCategoryResource;
use App\Models\Flow;
use App\Models\Software;
use App\Services\AssetNumberRuleService;
use App\Services\FlowHasFormService;
use App\Services\FlowHasNodeService;
use App\Services\FlowService;
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
                    /* @var Flow $flow */
                    $flow = SoftwareService::getRetireFlow();
                    if (! $flow) {
                        $flow_data['name'] = __('cat/software.action.retire_flow_name');
                        $flow_data['slug'] = 'retire_flow';
                        $flow_data['model_name'] = Software::class;
                        $flow_data['creator_id'] = 0;
                        $flow_service = new FlowService();
                        $flow = $flow_service->create($flow_data);
                    }
                    $data['flow_id'] = $flow->getKey();
                    $flow_has_node_service = new FlowHasNodeService();
                    $flow_has_node_service->batchCreate($data);
                    NotificationUtil::make(true, __('cat/software.action.set_retire_flow_success'));
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
                    /* @var Flow $flow */
                    $flow = SoftwareService::getRetireFlow();
                    if (! $flow->nodes()->count()) {
                        throw new Exception('cat/software.action.retire_flow_not_set');
                    }
                    $data['flow_has_node_id'] = $flow->nodes()->where('order', 0)->first()->getKey();
                    $asset_number = $software->getAttribute('asset_number');
                    $data['name'] = __('cat/software.action.retire_flow_name').' - '.$asset_number;
                    $data['model_name'] = Software::class;
                    $data['model_id'] = $software->getKey();
                    $flow_has_form_service = new FlowHasFormService();
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat/software.action.retire_success'));
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
