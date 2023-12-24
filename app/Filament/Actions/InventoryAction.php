<?php

namespace App\Filament\Actions;

use App\Filament\Forms\InventoryForm;
use App\Filament\Resources\InventoryResource;
use App\Models\Inventory;
use App\Services\InventoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class InventoryAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/inventory.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(InventoryForm::create())
            ->action(function (array $data) {
                try {
                    $data['creator_id'] = auth()->id();
                    $inventory_service = new InventoryService();
                    $inventory_service->create($data);
                    NotificationUtil::make(true, __('cat/inventory.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make(__('cat/inventory.action.delete'))
            ->slideOver()
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (Inventory $inventory) {
                try {
                    $inventory->service()->delete();
                    NotificationUtil::make(true, __('cat/inventory.action.delete_success'));
                    redirect(InventoryResource::getUrl('index'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
