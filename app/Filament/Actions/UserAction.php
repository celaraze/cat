<?php

namespace App\Filament\Actions;

use App\Models\User;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Filament\Tables\Actions\Action;

class UserAction
{
    /**
     * 清除密码.
     *
     * @return Action
     */
    public static function resetPassword(): Action
    {
        return Action::make('清除密码')
            ->color('warning')
            ->icon('heroicon-o-lock-open')
            ->requiresConfirmation()
            ->action(function (User $user) {
                try {
                    $user->service()->resetPassword();
                    NotificationUtil::make(true, '已清除密码，用户可在下次登陆时自行设定密码');
                } catch (\Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
