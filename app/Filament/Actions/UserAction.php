<?php

namespace App\Filament\Actions;

use App\Filament\Forms\UserForm;
use App\Models\User;
use App\Services\UserService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Auth\AuthenticationException;

class UserAction
{
    public static function resetPassword(): Action
    {
        return Action::make(__('cat/user.action.reset_password'))
            ->slideOver()
            ->color('warning')
            ->icon('heroicon-o-lock-open')
            ->requiresConfirmation()
            ->form(UserForm::resetPassword())
            ->action(function (User $user) {
                try {
                    $user->service()->changePassword('cat');
                    NotificationUtil::make(true, __('cat/user.action.reset_password_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    public static function changePassword(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('changePasswordAction')
            ->label(__('cat/user.action.change_password'))
            ->slideOver()
            ->icon('heroicon-m-key')
            ->form(UserForm::changePassword())
            ->action(function (array $data) {
                try {
                    if ($data['password'] != $data['password-verify']) {
                        throw new AuthenticationException(__('cat/user.action.change_password_failure_password_not_match'));
                    }
                    /* @var User $user */
                    $user = auth()->user();
                    $user->service()->changePassword($data['password']);
                    NotificationUtil::make(true, __('cat/user.action.change_password_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function changeAvatar(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('changeAvatarAction')
            ->label(__('cat/user.action.change_avatar'))
            ->slideOver()
            ->icon('heroicon-s-paint-brush')
            ->form(UserForm::changeAvatar())
            ->action(function (array $data) {
                try {
                    /* @var User $user */
                    $user = auth()->user();
                    $user->service()->changeAvatar($data['avatar']);
                    NotificationUtil::make(true, __('cat/user.action.change_avatar_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(): Action
    {
        return Action::make(__('cat/user.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(UserForm::create())
            ->action(function (array $data) {
                try {
                    $data['password'] = 'cat';
                    $data['password_verify'] = 'cat';
                    $user_service = new UserService();
                    $user_service->create($data);
                    NotificationUtil::make(true, __('cat/user.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/user.action.delete'))
            ->slideOver()
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->form(function (User $user) {
                $bool['device_has_users'] = ! $user->deviceHasUsers()->count();
                $bool['applicant_forms'] = ! $user->applicantForms()->whereNotIn('status', [3, 4])->count();
                $bool['approve_forms'] = ! $user->approvalForms()->whereNotIn('status', [3, 4])->count();

                return UserForm::delete($bool);
            })
            ->action(function (User $user) {
                try {
                    $user->service()->delete();
                    NotificationUtil::make(true, __('cat/user.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function forceDelete(): Action
    {
        return Action::make(__('cat/user.action.force_delete'))
            ->slideOver()
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalDescription(__('cat/user.action.force_delete_helper'))
            ->action(function (User $user) {
                try {
                    $user->service()->forceDelete();
                    NotificationUtil::make(true, __('cat/user.action.force_delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
