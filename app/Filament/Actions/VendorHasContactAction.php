<?php

namespace App\Filament\Actions;

use App\Filament\Forms\VendorHasContactForm;
use App\Models\Vendor;
use App\Services\VendorHasContactService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class VendorHasContactAction
{
    public static function create(?Model $out_vendor = null): Action
    {
        return Action::make(__('cat/vendor_has_contact.action.create'))
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
                    NotificationUtil::make(true, __('cat/vendor_has_contact.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
