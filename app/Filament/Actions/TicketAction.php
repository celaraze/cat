<?php

namespace App\Filament\Actions;

use App\Filament\Forms\TicketForm;
use App\Filament\Forms\TicketHasTrackForm;
use App\Models\Ticket;
use App\Services\TicketHasTrackService;
use App\Services\TicketService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class TicketAction
{
    public static function createHasTrack(Model $ticket): Action
    {
        /* @var Ticket $ticket */
        return Action::make(__('cat.action.create_ticket_has_track'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(TicketHasTrackForm::create())
            ->action(function (array $data) use ($ticket) {
                try {
                    $data['ticket_id'] = $ticket->getAttribute('id');
                    $data['user_id'] = auth()->id();
                    $ticket_has_track_service = new TicketHasTrackService();
                    $ticket_has_track_service->create($data);
                    NotificationUtil::make(true, __('cat.action.create_ticket_has_track_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(TicketForm::create())
            ->action(function (array $data) {
                try {
                    $ticket_service = new TicketService();
                    $ticket_service->create($data);
                    NotificationUtil::make(true, __('cat.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toCategory(): Action
    {
        return Action::make(__('cat.action.to_category'))
            ->icon('heroicon-s-square-3-stack-3d')
            ->url('/ticket-categories');
    }

    public static function finish(): \Filament\Infolists\Components\Actions\Action
    {
        /* @var Ticket $ticket */
        return \Filament\Infolists\Components\Actions\Action::make(__('cat.action.finish'))
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->button()
            ->action(function (Ticket $ticket) {
                try {
                    $ticket->service()->finish();
                    NotificationUtil::make(true, __('cat.action.finish_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setAssignee(): Action
    {
        return Action::make(__('cat.action.set_assignee'))
            ->icon('heroicon-o-hand-raised')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (Ticket $ticket) {
                try {
                    $assignee_id = auth()->id();
                    $ticket->service()->setAssignee($assignee_id);
                    NotificationUtil::make(true, __('cat.action.set_assignee_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
