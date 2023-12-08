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
    /**
     * 创建盘点.
     */
    public static function create(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(InventoryForm::create())
            ->action(function (array $data) {
                try {
                    $inventory_service = new InventoryService();
                    $inventory_service->create($data);
                    NotificationUtil::make(true, '已创建盘点');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 删除盘点.
     */
    public static function delete(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('放弃')
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->action(function (Inventory $inventory) {
                try {
                    $inventory->service()->delete();
                    NotificationUtil::make(true, '已放弃盘点');
                    redirect(InventoryResource::getUrl('index'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 盘点.
     */
    public static function check(): Action
    {
        return Action::make('盘点')
            ->slideOver()
            ->icon('heroicon-m-document-check')
            ->form(InventoryForm::check())
            ->action(function (array $data, InventoryHasTrack $inventory_has_track) {
                try {
                    $inventory_has_track->service()->check($data);
                    NotificationUtil::make(true, '已盘点');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
