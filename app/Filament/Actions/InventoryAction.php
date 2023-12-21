<?php

namespace App\Filament\Actions;

use App\Filament\Forms\InventoryForm;
use App\Filament\Resources\InventoryResource;
use App\Models\Inventory;
use App\Models\InventoryHasTrack;
use App\Services\InventoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class InventoryAction
{
    public static function create(): Action
    {
        return Action::make(__('cat.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(InventoryForm::create())
            ->action(function (array $data) {
                try {
                    $inventory_service = new InventoryService();
                    $inventory_service->create($data);
                    NotificationUtil::make(true, __('cat.action.created'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make(__('cat.action.delete'))
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (Inventory $inventory) {
                try {
                    $inventory->service()->delete();
                    NotificationUtil::make(true, __('cat.action.delete_success'));
                    redirect(InventoryResource::getUrl('index'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function check(): Action
    {
        return Action::make(__('cat.action.inventory_check'))
            ->slideOver()
            ->icon('heroicon-m-document-check')
            ->form(InventoryForm::check())
            ->action(function (array $data, InventoryHasTrack $inventory_has_track) {
                try {
                    $inventory_has_track->service()->check($data);
                    NotificationUtil::make(true, __('cat.action.inventory_check_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
