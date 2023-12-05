<?php

namespace App\Filament\Actions;

use App\Filament\Forms\TicketCategoryForm;
use App\Filament\Forms\TicketForm;
use App\Filament\Forms\TicketHasTrackForm;
use App\Models\Ticket;
use App\Services\TicketCategoryService;
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
    public static function createTicket(): Action
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
     * 创建工单分类按钮.
     */
    public static function createTicketCategory(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(TicketCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $ticket_category_service = new TicketCategoryService();
                    $ticket_category_service->create($data);
                    NotificationUtil::make(true, '已创建分类');
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
    public static function createTicketHasTrack(Model $ticket): Action
    {
        /* @var $ticket Ticket */
        return Action::make('发表评论')
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
     * 前往设备分类.
     */
    public static function toTicketCategory(): Action
    {
        return Action::make('分类')
            ->icon('heroicon-s-square-3-stack-3d')
            ->url('/ticket-categories');
    }

    /**
     * 前往设备.
     */
    public static function toTicket(): Action
    {
        return Action::make('返回工单')
            ->icon('heroicon-s-server')
            ->url('/tickets');
    }

    /**
     * 标记完成.
     */
    public static function finish(Model $ticket): \Filament\Actions\Action
    {
        /* @var $ticket Ticket */
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
            });
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
            });
    }
}
