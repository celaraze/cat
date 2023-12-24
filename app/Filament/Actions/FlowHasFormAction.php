<?php

namespace App\Filament\Actions;

use App\Filament\Forms\FlowHasFormForm;
use App\Models\FlowHasForm;
use App\Services\FlowHasFormService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;

class FlowHasFormAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/flow_has_form.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(FlowHasFormForm::create())
            ->action(function (array $data) {
                try {
                    $flow_has_form_service = new FlowHasFormService();
                    $flow_has_form_service->create($data);
                    NotificationUtil::make(true, __('cat/flow_has_form.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function approve(): \Filament\Infolists\Components\Actions\Action
    {
        return \Filament\Infolists\Components\Actions\Action::make(__('cat/flow_has_form.action.approve'))
            ->slideOver()
            ->icon('heroicon-o-shield-exclamation')
            ->form(FlowHasFormForm::approve())
            ->action(function (array $data, FlowHasForm $flow_has_form) {
                try {
                    $flow_has_form_service = new FlowHasFormService($flow_has_form);
                    $flow_has_form_service->approve($data['status'], $data['approve_comment']);
                    NotificationUtil::make(true, __('cat/flow.action.approve_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception->getMessage());
                }
            })
            ->closeModalByClickingAway(false);
    }
}
