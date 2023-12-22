<?php

namespace App\Filament\Actions;

use App\Filament\Forms\BrandForm;
use App\Models\Brand;
use App\Services\BrandService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class BrandAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/brand.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(BrandForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $brand_service = new BrandService();
                    $brand_service->create($data);
                    NotificationUtil::make(true, __('cat/brand.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/brand.action.delete'))
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(BrandForm::delete())
            ->action(function (Brand $brand) {
                try {
                    $brand->delete();
                    NotificationUtil::make(true, __('cat/brand.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
