<?php

namespace App\Filament\Actions;

use App\Filament\Forms\VendorForm;
use App\Models\Vendor;
use App\Services\VendorService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class VendorAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/vendor.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(VendorForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $vendor_service = new VendorService();
                    $vendor_service->create($data);
                    NotificationUtil::make(true, __('cat/vendor.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/vendor.action.delete'))
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(VendorForm::delete())
            ->action(function (Vendor $vendor) {
                try {
                    $vendor->service()->delete();
                    NotificationUtil::make(true, __('cat/vendor.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
