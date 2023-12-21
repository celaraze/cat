<?php

namespace App\Filament\Actions;

use App\Filament\Forms\PartCategoryForm;
use App\Filament\Resources\PartResource;
use App\Models\Part;
use App\Models\PartCategory;
use App\Services\PartCategoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class PartCategoryAction
{
    public static function create(): Action
    {
        return Action::make(__('cat.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(PartCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $part_category_service = new PartCategoryService();
                    $part_category_service->create($data);
                    NotificationUtil::make(true, __('cat.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat.action.delete'))
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(PartCategoryForm::delete())
            ->action(function (PartCategory $part_category) {
                try {
                    $part_category->service()->delete();
                    NotificationUtil::make(true, __('cat.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    public static function backToPart(): Action
    {
        return Action::make(__('cat.action.back_to_part'))
            ->icon('heroicon-m-cpu-chip')
            ->url(PartResource::getUrl('index'));
    }

    public static function toPartView(): Action
    {
        return Action::make(__('cat.action.to_part_view'))
            ->icon('heroicon-m-cpu-chip')
            ->url(function (Part $part) {
                return PartResource::getUrl('view', ['record' => $part->getKey()]);
            });
    }
}
