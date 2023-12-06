<?php

namespace App\Filament\Actions;

use App\Filament\Forms\VendorForm;
use App\Filament\Forms\VendorHasContactForm;
use App\Models\Vendor;
use App\Services\VendorService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class VendorAction
{
    /**
     * 创建联系人按钮.
     */
    public static function createVendorHasContact(?Model $out_vendor = null): Action
    {
        return Action::make('添加联系人')
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(VendorHasContactForm::createOrEdit())
            ->action(function (array $data, Vendor $vendor) use ($out_vendor) {
                try {
                    if ($out_vendor) {
                        $vendor = $out_vendor;
                    }
                    $data = [
                        'name' => $data['name'],
                        'phone_number' => $data['phone_number'],
                        'email' => $data['email'],
                    ];
                    $vendor->service()->createHasContacts($data);
                    NotificationUtil::make(true, '已添加联系人');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 创建厂商.
     */
    public static function createVendor(): Action
    {
        return Action::make('创建厂商')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(VendorForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $vendor_service = new VendorService();
                    $vendor_service->create($data);
                    NotificationUtil::make(true, '已创建厂商');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
