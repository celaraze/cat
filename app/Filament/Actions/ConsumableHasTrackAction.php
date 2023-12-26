<?php

namespace App\Filament\Actions;

use App\Filament\Forms\ConsumableHasTrackForm;
use App\Models\Consumable;
use App\Models\ConsumableHasTrack;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class ConsumableHasTrackAction
{
    public static function create(Model $consumable): Action
    {
        /** @var Consumable $consumable */
        return Action::make(__('cat/consumable_has_track.action.create'))
            ->slideOver()
            ->icon('heroicon-o-plus-circle')
            ->form(ConsumableHasTrackForm::create($consumable))
            ->action(function (array $data) use ($consumable) {
                try {
                    $data['consumable_id'] = $consumable->getKey();
                    $consumable_has_track = new ConsumableHasTrack();
                    $consumable_has_track->service()->create($data);
                    NotificationUtil::make(true, __('cat/consumable_has_track.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
