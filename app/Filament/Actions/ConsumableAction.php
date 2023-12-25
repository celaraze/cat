<?php

namespace App\Filament\Actions;

use App\Filament\Forms\ConsumableForm;
use App\Filament\Resources\ConsumableCategoryResource;
use App\Filament\Resources\ConsumableUnitResource;
use App\Models\Consumable;
use App\Services\ConsumableService;
use App\Services\FlowHasFormService;
use App\Services\SettingService;
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
                    $consumable_retire_flow = $consumable->service()->getRetireFlow();
                    $consumable_id = $consumable->getKey();
                    $flow_has_form_service = new FlowHasFormService();
                    $data['flow_id'] = $consumable_retire_flow->getKey();
                    $data['name'] = __('cat/device.action.retire_flow_name').' - '.$consumable_id;
                    $data['payload'] = $consumable_id;
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
                    $setting_service = new SettingService();
                    $setting_service->set('consumable_retire_flow_id', $data['flow_id']);
                    NotificationUtil::make(true, __('cat/consumable.action.set_retire_flow_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
