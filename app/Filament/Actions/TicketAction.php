<?php

namespace App\Filament\Actions;

use App\Filament\Forms\TicketForm;
use App\Models\Device;
use App\Models\Ticket;
use App\Services\TicketService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class TicketAction
{
    public static function create($model = null): Action
    {
        return Action::make(__('cat/ticket.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(TicketForm::create($model))
            ->action(function (array $data) use ($model) {
                try {
                    if ($model instanceof Device) {
                        $data['asset_number'] = $model->getAttribute('asset_number');
                    }
                    $data['user_id'] = auth()->id();
                    $ticket_service = new TicketService();
                    $ticket_service->create($data);
                    NotificationUtil::make(true, __('cat/ticket.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function toCategory(): Action
    {
        return Action::make(__('cat/menu.ticket_category'))
            ->icon('heroicon-s-square-3-stack-3d')
            ->url('/ticket-categories');
    }

    public static function finish(): \Filament\Infolists\Components\Actions\Action
    {
        /* @var Ticket $ticket */
        return \Filament\Infolists\Components\Actions\Action::make(__('cat/ticket.action.finish'))
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->button()
            ->action(function (Ticket $ticket) {
                try {
                    $ticket->service()->finish();
                    NotificationUtil::make(true, __('cat/ticket.action.finish_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function setAssignee(): Action
    {
        return Action::make(__('cat/ticket.action.set_assignee'))
            ->icon('heroicon-o-hand-raised')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (Ticket $ticket) {
                try {
                    $assignee_id = auth()->id();
                    $ticket->service()->setAssignee($assignee_id);
                    NotificationUtil::make(true, __('cat/ticket.action.set_assignee_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
