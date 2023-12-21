<?php

namespace App\Filament\Actions;

use App\Filament\Forms\VendorForm;
use App\Filament\Forms\VendorHasContactForm;
use App\Models\Vendor;
use App\Services\VendorHasContactService;
use App\Services\VendorService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class VendorAction
{
    public static function createHasContact(?Model $out_vendor = null): Action
    {
        return Action::make(__('cat.action.create_vendor_has_contact'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(VendorHasContactForm::createOrEdit())
            ->action(function (array $data, Vendor $vendor) use ($out_vendor) {
                try {
                    if ($out_vendor) {
                        $vendor = $out_vendor;
                    }
                    $data['vendor_id'] = $vendor->getKey();
                    $vendor_has_contact_service = new VendorHasContactService();
                    $vendor_has_contact_service->create($data);
                    NotificationUtil::make(true, __('cat.action.create_vendor_has_contact_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(VendorForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $vendor_service = new VendorService();
                    $vendor_service->create($data);
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
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(VendorForm::delete())
            ->action(function (Vendor $vendor) {
                try {
                    $vendor->service()->delete();
                    NotificationUtil::make(true, __('cat.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
