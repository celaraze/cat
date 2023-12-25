<?php

namespace App\Filament\Actions;

use App\Filament\Forms\OrganizationHasUserForm;
use App\Models\Organization;
use App\Models\OrganizationHasUser;
use App\Services\OrganizationHasUserService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class OrganizationHasUserAction
{
    public static function create(?Model $out_organization = null): Action
    {
        return Action::make(__('cat/organization_has_user.action.create'))
            ->slideOver()
            ->icon('heroicon-s-user-plus')
            ->form(OrganizationHasUserForm::create())
            ->action(function (array $data, Organization $organization) use ($out_organization) {
                try {
                    if ($out_organization) {
                        $organization = $out_organization;
                    }
                    $data['organization_id'] = $organization->getKey();
                    $organization_has_user = new OrganizationHasUserService();
                    $organization_has_user->batchCreate($data);
                    NotificationUtil::make(true, __('cat/organization_has_user.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/organization_has_user.action.delete'))
            ->slideOver()
            ->requiresConfirmation()
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->action(function (OrganizationHasUser $organization_has_user) {
                try {
                    $organization_has_user->service()->delete();
                    NotificationUtil::make(true, __('cat/organization_has_user.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
