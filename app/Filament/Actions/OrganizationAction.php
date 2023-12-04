<?php

namespace App\Filament\Actions;

use App\Filament\Forms\OrganizationForm;
use App\Models\Organization;
use App\Models\OrganizationHasUser;
use App\Services\OrganizationService;
use App\Services\UserService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;

class OrganizationAction
{
    /**
     * 编辑组织按钮.
     */
    public static function updateOrganization(): \SolutionForest\FilamentTree\Actions\Action
    {
        return \SolutionForest\FilamentTree\Actions\Action::make('编辑')
            ->slideOver()
            ->icon('heroicon-s-pencil-square')
            ->link()
            ->form(OrganizationForm::createOrEdit())
            ->action(function (array $data, Organization $organization) {
                try {

                    $data = [
                        'name' => $data['name'],
                    ];
                    $organization->service()->update($data);
                    NotificationUtil::make(true, '已修改组织');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 创建组织按钮.
     */
    public static function createOrganization(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(OrganizationForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $organization_service = new OrganizationService();
                    $organization_service->create($data);
                    NotificationUtil::make(true, '已创建组织');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 删除组织按钮.
     */
    public static function deleteOrganization(): \SolutionForest\FilamentTree\Actions\Action
    {
        return \SolutionForest\FilamentTree\Actions\Action::make('删除')
            ->requiresConfirmation()
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->link()
            ->action(function (Organization $organization) {
                try {
                    $organization->service()->delete();
                    NotificationUtil::make(true, '已删除组织');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 新增组织用户记录.
     */
    public static function createHasUser(Model $out_organization = null): \Filament\Tables\Actions\Action
    {
        return \Filament\Tables\Actions\Action::make('新增成员')
            ->slideOver()
            ->icon('heroicon-s-user-plus')
            ->form([
                Select::make('user_ids')
                    ->label('成员')
                    ->options(UserService::pluckOptions('id', UserService::existOrganizationHasUserIds()))
                    ->multiple()
                    ->searchable(),
            ])
            ->action(function (array $data, Organization $organization) use ($out_organization) {
                try {
                    if ($out_organization) {
                        $organization = $out_organization;
                    }
                    $data['organization_id'] = $organization->getKey();
                    $organization->service()->createManyHasUsers($data);
                    NotificationUtil::make(true, '已新增成员');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 删除组织用户记录.
     */
    public static function deleteHasUser(): \Filament\Tables\Actions\Action
    {
        return \Filament\Tables\Actions\Action::make('删除')
            ->requiresConfirmation()
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->action(function (OrganizationHasUser $organization_has_user) {
                try {
                    $organization_has_user->service()->delete();
                    NotificationUtil::make(true, '已删除用户记录');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
