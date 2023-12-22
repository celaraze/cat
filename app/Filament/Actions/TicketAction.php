<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceForm;
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
    public static function createFromDevice($asset_number = null): Action
    {
        return Action::make(__('cat/ticket.action.create_from_device'))
            ->icon('heroicon-m-plus-circle')
            ->slideOver()
            ->form(function (Device $device) use ($asset_number) {
                if (! $asset_number) {
                    $asset_number = $device->getAttribute('asset_number');
                }

                return DeviceForm::createTicketFromDevice($asset_number);
            })
            ->action(function (array $data, Device $device) use ($asset_number) {
                try {
                    if (! $asset_number) {
                        $asset_number = $device->getAttribute('asset_number');
                    }
                    $data['asset_number'] = $asset_number;
                    $data['user_id'] = auth()->id();
                    $ticket_service = new TicketService();
                    $ticket_service->create($data);
                    NotificationUtil::make(true, __('cat/ticket.action.created_from_device_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat/ticket.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(TicketForm::create())
            ->action(function (array $data) {
                try {
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
