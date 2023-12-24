<?php

namespace App\Filament\Actions;

use App\Filament\Forms\VendorHasContactForm;
use App\Models\VendorHasContact;
use App\Services\VendorHasContactService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class VendorHasContactAction
{
    public static function create($model): Action
    {
        return Action::make(__('cat/vendor_has_contact.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(VendorHasContactForm::createOrEdit())
            ->action(function (array $data) use ($model) {
                try {
                    $data['vendor_id'] = $model->getKey();
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

    public static function delete(): Action
    {
        return Action::make(__('cat/vendor_has_contact.action.delete'))
            ->slideOver()
            ->color('danger')
            ->requiresConfirmation()
            ->icon('heroicon-o-trash')
            ->action(function (VendorHasContact $vendor_has_contact) {
                try {
                    $vendor_has_contact->service()->delete();
                    NotificationUtil::make(true, __('cat/vendor_has_contact.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
