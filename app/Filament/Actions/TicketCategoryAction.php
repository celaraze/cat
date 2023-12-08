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
    /**
     * 创建工单分类按钮.
     */
    public static function create(): Action
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
     * 删除工单分类.
     */
    public static function delete(): Action
    {
        return Action::make('删除')
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(TicketCategoryForm::delete())
            ->action(function (TicketCategory $ticket_category) {
                try {
                    $ticket_category->service()->delete();
                    NotificationUtil::make(true, '已删除工单分类');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
