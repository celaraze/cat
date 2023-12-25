<?php

namespace App\Filament\Actions;

use App\Filament\Forms\ConsumableUnitForm;
use App\Filament\Resources\ConsumableResource;
use App\Models\ConsumableUnit;
use App\Services\ConsumableUnitService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class ConsumableUnitAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/consumable_unit.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(ConsumableUnitForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $consumable_unit_service = new ConsumableUnitService();
                    $consumable_unit_service->create($data);
                    NotificationUtil::make(true, __('cat/consumable_unit.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/consumable_unit.action.delete'))
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->slideOver()
            ->form(ConsumableUnitForm::delete())
            ->action(function (ConsumableUnit $consumable_unit) {
                try {
                    $consumable_unit->service()->delete();
                    NotificationUtil::make(true, __('cat/consumable_unit.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toConsumable(): Action
    {
        return Action::make(__('cat/consumable_category.action.to_consumable'))
            ->icon('heroicon-o-beaker')
            ->url(ConsumableResource::getUrl('index'));
    }
}
