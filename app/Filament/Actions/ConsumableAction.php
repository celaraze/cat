<?php

namespace App\Filament\Actions;

use App\Filament\Forms\ConsumableForm;
use App\Filament\Resources\ConsumableCategoryResource;
use App\Filament\Resources\ConsumableUnitResource;
use App\Models\Consumable;
use App\Models\Flow;
use App\Services\ConsumableService;
use App\Services\FlowHasFormService;
use App\Services\FlowHasNodeService;
use App\Services\FlowService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class ConsumableAction
{
    public static function toCategory(): Action
    {
        return Action::make(__('cat/menu.consumable_category'))
            ->icon('heroicon-s-square-3-stack-3d')
            ->url(ConsumableCategoryResource::getUrl('index'));
    }

    public static function toUnit(): Action
    {
        return Action::make(__('cat/menu.consumable_unit'))
            ->icon('heroicon-s-square-3-stack-3d')
            ->url(ConsumableUnitResource::getUrl('index'));
    }

    public static function forceRetire(): Action
    {
        return Action::make(__('cat/consumable.action.force_retire'))
            ->slideOver()
            ->requiresConfirmation()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(ConsumableForm::forceRetire())
            ->action(function (Consumable $consumable) {
                try {
                    $consumable->service()->retire();
                    NotificationUtil::make(true, __('cat/consumable.action.force_retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function retire(): Action
    {
        return Action::make(__('cat/consumable.action.retire'))
            ->slideOver()
            ->icon('heroicon-m-archive-box-x-mark')
            ->form(ConsumableForm::retire())
            ->action(function (array $data, Consumable $consumable) {
                try {
                    /* @var Flow $flow */
                    $flow = ConsumableService::getRetireFlow();
                    if (! $flow->nodes()->count()) {
                        throw new Exception('cat/consumable.action.retire_flow_not_set');
                    }
                    $data['flow_has_node_id'] = $flow->nodes()->where('order', 0)->first()->getKey();
                    $asset_number = $consumable->getAttribute('asset_number');
                    $data['name'] = __('cat/consumable.action.retire_flow_name').' - '.$asset_number;
                    $data['model_name'] = Consumable::class;
                    $data['model_id'] = $consumable->getKey();
                    $flow_has_form_service = new FlowHasFormService();
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat/consumable.action.retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat/consumable.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(ConsumableForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $consumable_service = new ConsumableService();
                    $consumable_service->create($data);
                    NotificationUtil::make(true, __('cat/consumable.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setRetireFlow(): Action
    {
        return Action::make(__('cat/consumable.action.set_retire_flow'))
            ->slideOver()
            ->form(ConsumableForm::setRetireFlow())
            ->action(function (array $data) {
                try {
                    /* @var Flow $flow */
                    $flow = ConsumableService::getRetireFlow();
                    if (! $flow) {
                        $flow_data['name'] = __('cat/consumable.action.retire_flow_name');
                        $flow_data['slug'] = 'retire_flow';
                        $flow_data['model_name'] = Consumable::class;
                        $flow_data['creator_id'] = 0;
                        $flow_service = new FlowService();
                        $flow = $flow_service->create($flow_data);
                    }
                    $data['flow_id'] = $flow->getKey();
                    $flow_has_node_service = new FlowHasNodeService();
                    $flow_has_node_service->batchCreate($data);
                    NotificationUtil::make(true, __('cat/consumable.action.set_retire_flow_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
