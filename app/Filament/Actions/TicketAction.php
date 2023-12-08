<?php

namespace App\Filament\Actions;

use App\Filament\Forms\TicketForm;
use App\Filament\Forms\TicketHasTrackForm;
use App\Models\Ticket;
use App\Services\TicketService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class TicketAction
{
    /**
     * 创建工单按钮.
     */
    public static function create(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(TicketForm::create())
            ->action(function (array $data) {
                try {
                    $ticket_service = new TicketService();
                    $ticket_service->create($data);
                    NotificationUtil::make(true, '已创建工单');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 创建工单记录按钮.
     */
    public static function createHasTrack(Model $ticket): Action
    {
        /* @var Ticket $ticket */
        return Action::make('发表评论')
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(TicketHasTrackForm::create())
            ->action(function (array $data) use ($ticket) {
                try {
                    $data['user_id'] = auth()->id();
                    $ticket->service()->createHasTrack($data);
                    NotificationUtil::make(true, '已发表评论');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 前往工单分类.
     */
    public static function toCategory(): Action
    {
        return Action::make('分类')
            ->icon('heroicon-s-square-3-stack-3d')
            ->url('/ticket-categories');
    }

    /**
     * 前往工单.
     */
    public static function toTickets(): Action
    {
        return Action::make('返回工单')
            ->icon('heroicon-o-document-text')
            ->url('/tickets');
    }

    /**
     * 标记完成.
     */
    public static function finish(Model $ticket): \Filament\Actions\Action
    {
        /* @var Ticket $ticket  */
        return \Filament\Actions\Action::make('标记完成')
            ->color('success')
            ->icon('heroicon-o-check-circle')
            ->requiresConfirmation()
            ->action(function () use ($ticket) {
                try {
                    $ticket->service()->finish();
                    NotificationUtil::make(true, '已标记完成');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 接单按钮.
     */
    public static function setAssignee(): Action
    {
        return Action::make('接单')
            ->icon('heroicon-o-hand-raised')
            ->color('success')
            ->requiresConfirmation()
            ->action(function (Ticket $ticket) {
                try {
                    $user_id = auth()->id();
                    $ticket->service()->setAssignee($user_id);
                    NotificationUtil::make(true, '已接单');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
