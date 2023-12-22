<?php

namespace App\Filament\Actions;

use App\Filament\Forms\TicketCategoryForm;
use App\Models\TicketCategory;
use App\Services\TicketCategoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class TicketCategoryAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/ticket_category.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(TicketCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $ticket_category_service = new TicketCategoryService();
                    $ticket_category_service->create($data);
                    NotificationUtil::make(true, __('cat/ticket_category.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/ticket_category.action.delete'))
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(TicketCategoryForm::delete())
            ->action(function (TicketCategory $ticket_category) {
                try {
                    $ticket_category->service()->delete();
                    NotificationUtil::make(true, __('cat/ticket_category.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    public static function toTicket(): Action
    {
        return Action::make(__('cat/ticket_category.action.to_ticket'))
            ->icon('heroicon-o-document-text')
            ->url('/tickets');
    }
}
