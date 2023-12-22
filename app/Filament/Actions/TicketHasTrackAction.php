<?php

namespace App\Filament\Actions;

use App\Filament\Forms\TicketHasTrackForm;
use App\Models\Ticket;
use App\Services\TicketHasTrackService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class TicketHasTrackAction
{
    public static function create(Model $ticket): Action
    {
        /* @var Ticket $ticket */
        return Action::make(__('cat/ticket_has_track.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(TicketHasTrackForm::create())
            ->action(function (array $data) use ($ticket) {
                try {
                    $data['ticket_id'] = $ticket->getAttribute('id');
                    $data['user_id'] = auth()->id();
                    $ticket_has_track_service = new TicketHasTrackService();
                    $ticket_has_track_service->create($data);
                    NotificationUtil::make(true, __('cat/ticket_has_track.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
