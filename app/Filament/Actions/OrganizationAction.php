<?php

namespace App\Filament\Actions;

use App\Filament\Forms\OrganizationForm;
use App\Services\OrganizationService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Actions\Action;

class OrganizationAction
{
    public static function create(): Action
    {
        return Action::make(__('cat/organization.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(OrganizationForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $organization_service = new OrganizationService();
                    $organization_service->create($data);
                    NotificationUtil::make(true, __('cat/organization.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
