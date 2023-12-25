<?php

namespace App\Filament\Actions;

use App\Filament\Forms\ConsumableCategoryForm;
use App\Filament\Resources\ConsumableResource;
use App\Models\Consumable;
use App\Models\ConsumableCategory;
use App\Services\ConsumableCategoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class ConsumableCategoryAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/consumable_category.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(ConsumableCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $consumable_category_service = new ConsumableCategoryService();
                    $consumable_category_service->create($data);
                    NotificationUtil::make(true, __('cat/consumable_category.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/consumable_category.action.delete'))
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->slideOver()
            ->form(ConsumableCategoryForm::delete())
            ->action(function (ConsumableCategory $consumable_category) {
                try {
                    $consumable_category->service()->delete();
                    NotificationUtil::make(true, __('cat/consumable_category.action.delete_success'));
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

    public static function toConsumableView(): Action
    {
        return Action::make(__('cat/consumable_category.action.to_consumable_view'))
            ->icon('heroicon-o-beaker')
            ->url(function (Consumable $consumable) {
                return ConsumableResource::getUrl('view', ['record' => $consumable->getKey()]);
            });
    }
}
