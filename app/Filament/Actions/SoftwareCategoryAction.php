<?php

namespace App\Filament\Actions;

use App\Filament\Forms\SoftwareCategoryForm;
use App\Filament\Resources\SoftwareResource;
use App\Models\Software;
use App\Models\SoftwareCategory;
use App\Services\SoftwareCategoryService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class SoftwareCategoryAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/software_category.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SoftwareCategoryForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $software_category_service = new SoftwareCategoryService();
                    $software_category_service->create($data);
                    NotificationUtil::make(true, __('cat/software_category.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/software_category.action.delete'))
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(SoftwareCategoryForm::delete())
            ->action(function (SoftwareCategory $software_category) {
                try {
                    $software_category->service()->delete();
                    NotificationUtil::make(true, __('cat/software_category.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    public static function toSoftware(): Action
    {
        return Action::make(__('cat/software_category.action.to_software'))
            ->icon('heroicon-m-squares-plus')
            ->url(SoftwareResource::getUrl('index'));
    }

    public static function toSoftwareView(): Action
    {
        return Action::make(__('cat/software_category.action.to_software_view'))
            ->icon('heroicon-m-squares-plus')
            ->url(function (Software $software) {
                return SoftwareResource::getUrl('view', ['record' => $software->getKey()]);
            });
    }
}
