<?php

namespace App\Filament\Actions;

use App\Filament\Forms\InventoryHasTrackForm;
use App\Models\InventoryHasTrack;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class InventoryHasTrackAction
{
    public static function check(): Action
    {
        return Action::make(__('cat/inventory_has_track.action.check'))
            ->slideOver()
            ->icon('heroicon-m-document-check')
            ->form(InventoryHasTrackForm::check())
            ->action(function (array $data, InventoryHasTrack $inventory_has_track) {
                try {
                    $inventory_has_track->service()->check($data);
                    NotificationUtil::make(true, __('cat/inventory_has_track.action.check_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
