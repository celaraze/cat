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
        return Action::make(__('cat.action.reset_password'))
            ->color('warning')
            ->icon('heroicon-o-lock-open')
            ->requiresConfirmation()
            ->form(UserForm::resetPassword())
            ->action(function (User $user) {
                try {
                    $user->service()->changePassword('cat');
                    NotificationUtil::make(true, __('cat.action.reset_password_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    public static function changePassword(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('changePasswordAction')
            ->label(__('cat.change_password'))
            ->slideOver()
            ->icon('heroicon-m-key')
            ->form(UserForm::changePassword())
            ->action(function (array $data) {
                try {
                    if ($data['password'] != $data['password-verify']) {
                        throw new AuthenticationException(__('cat.action.change_password_failure_password_not_match'));
                    }
                    /* @var User $user */
                    $user = auth()->user();
                    $user->service()->changePassword($data['password']);
                    NotificationUtil::make(true, __('cat.action.change_password_success'));
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
            ->label(__('cat.action.change_avatar'))
            ->slideOver()
            ->icon('heroicon-s-paint-brush')
            ->form(UserForm::changeAvatar())
            ->action(function (array $data) {
                try {
                    /* @var User $user */
                    $user = auth()->user();
                    $user->service()->changeAvatar($data['avatar']);
                    NotificationUtil::make(true, __('cat.action.change_avatar_success'));
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
            ->form(UserForm::create())
            ->action(function (array $data) {
                try {
                    $data['password'] = 'cat';
                    $data['password_verify'] = 'cat';
                    $user_service = new UserService();
                    $user_service->create($data);
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
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->form(function (User $user) {
                $bool['device_has_users'] = ! $user->deviceHasUsers()->count();
                $bool['applicant_forms'] = ! $user->applicantForms()->whereNotIn('status', [3, 4])->count();
                $bool['approve_forms'] = ! $user->approvalForms()->whereNotIn('status', [3, 4])->count();
                $bool['approve_nodes'] = ! $user->approvalNodes()->count();

                return UserForm::delete($bool);
            })
            ->action(function (User $user) {
                try {
                    $user->service()->delete();
                    NotificationUtil::make(true, __('cat.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    public static function forceDelete(): Action
    {
        return Action::make(__('cat.action.force_delete'))
            ->icon('heroicon-s-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->modalDescription(__('cat.action.force_delete_helper'))
            ->action(function (User $user) {
                try {
                    $user->service()->forceDelete();
                    NotificationUtil::make(true, __('cat.action.force_delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
