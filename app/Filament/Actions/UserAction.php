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
    /**
     * 清除密码.
     */
    public static function resetPassword(): Action
    {
        return Action::make('清除密码')
            ->color('warning')
            ->icon('heroicon-o-lock-open')
            ->requiresConfirmation()
            ->form(UserForm::resetPassword())
            ->action(function (User $user) {
                try {
                    $user->service()->changePassword('cat');
                    NotificationUtil::make(true, '已清除密码，用户可在下次登陆时自行设定密码');
                } catch (\Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    /**
     * 修改密码.
     */
    public static function changePassword(): \Filament\Actions\Action
    {
        return \Filament\Actions\Action::make('changePasswordAction')
            ->label('修改密码')
            ->slideOver()
            ->icon('heroicon-o-lock-closed')
            ->form(UserForm::changePassword())
            ->action(function (array $data) {
                try {
                    if ($data['password'] != $data['password-verify']) {
                        throw new AuthenticationException('密码不一致');
                    }
                    /* @var User $user */
                    $user = auth()->user();
                    $user->service()->changePassword($data['password']);
                    NotificationUtil::make(true, '已修改密码');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 创建用户.
     */
    public static function createUser(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(UserForm::create())
            ->action(function (array $data) {
                try {
                    $data['password'] = 'cat';
                    $data['password_verify'] = 'cat';
                    $user_service = new UserService();
                    $user_service->create($data);
                    NotificationUtil::make(true, '已创建用户');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 删除用户按钮.
     */
    public static function deleteUser(): Action
    {
        return Action::make('删除')
            ->color('danger')
            ->icon('heroicon-o-trash')
            ->requiresConfirmation()
            ->form(function (User $user) {
                $bool['device_has_users'] = !$user->deviceHasUsers()->count();
                $bool['applicant_forms'] = !$user->applicantForms()->whereNotIn('status', [3, 4])->count();
                $bool['approve_forms'] = !$user->approvalForms()->whereNotIn('status', [3, 4])->count();
                $bool['approve_nodes'] = !$user->approvalNodes()->count();

                return UserForm::delete($bool);
            })
            ->action(function (User $user) {
                try {
                    $user->service()->delete();
                    NotificationUtil::make(true, '已删除用户');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
