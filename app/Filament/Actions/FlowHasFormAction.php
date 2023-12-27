<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceForm;
use App\Filament\Forms\FlowHasFormForm;
use App\Services\FlowHasFormService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class FlowHasFormAction
{
    public static function create(): Action
    {
        return Action::make()
            ->requiresConfirmation()
            ->slideOver()
            ->form(DeviceForm::retire())
            ->action(function (array $data) {
                try {
                    $flow_has_form_service = new FlowHasFormService();
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat/device.action.retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    public static function approve()
    {
        return Action::make(__('cat/flow_has_form.action.approve'))
            ->requiresConfirmation()
            ->slideOver()
            ->form(FlowHasFormForm::approve())
            ->action(function (array $data) {
                try {
                    $flow_has_form_service = new FlowHasFormService();
                    $flow_has_form_service->approve($data);
                    NotificationUtil::make(true, __('cat/flow_has_form.action.approve_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
