<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceHasSecretForm;
use App\Filament\Forms\SecretForm;
use App\Models\DeviceHasSecret;
use App\Models\Secret;
use App\Services\DeviceHasSecretService;
use App\Services\SecretService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class SecretAction
{
    /**
     * 查看密码.
     */
    public static function token(): Action
    {
        return Action::make('查看密码')
            ->icon('heroicon-m-key')
            ->color('warning')
            ->requiresConfirmation()
            ->modalDescription('请验证您的身份，通过后密码将以通知形式展示在右上角。您可以查看并复制密码，并自行关闭消息。')
            ->form(SecretForm::viewToken())
            ->action(function (array $data, Secret $secret) {
                if (auth()->attempt(['email' => auth()->user()->email, 'password' => $data['password']])) {
                    NotificationUtil::make(true, '密码：'.$secret->getAttribute('token'), true);
                } else {
                    NotificationUtil::make(false, '密码错误');
                }
            });
    }

    /**
     * 创建密钥-设备按钮.
     */
    public static function createDeviceHasSecret(?Model $out_secret = null): Action
    {
        /* @var Secret $out_secret */
        return Action::make('附加到设备')
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasSecretForm::createFromSecret($out_secret))
            ->action(function (array $data, Secret $secret) use ($out_secret) {
                try {
                    if ($out_secret) {
                        $secret = $out_secret;
                    }
                    $data['secret_id'] = $secret->getKey();
                    $data['creator_id'] = auth()->id();
                    $data['status'] = 0;
                    $device_has_secret_service = new DeviceHasSecretService();
                    $device_has_secret_service->create($data);
                    NotificationUtil::make(true, '密钥已附加到设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 创建按钮.
     */
    public static function create(): Action
    {
        return Action::make('新增')
            ->slideOver()
            ->icon('heroicon-m-plus')
            ->form(SecretForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $data['creator_id'] = auth()->id();
                    $secret_service = new SecretService();
                    $secret_service->create($data);
                    NotificationUtil::make(true, '已创建密钥');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 密钥脱离设备按钮.
     */
    public static function deleteDeviceHasSecret(): Action
    {
        return Action::make('脱离')
            ->icon('heroicon-s-minus-circle')
            ->requiresConfirmation()
            ->color('danger')
            ->action(function (DeviceHasSecret $device_has_secret) {
                try {
                    $data = [
                        'creator_id' => auth()->id(),
                        'status' => 1,
                    ];
                    $device_has_secret->service()->delete($data);
                    NotificationUtil::make(true, '密钥已脱离设备');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    /**
     * 删除按钮.
     */
    public static function delete(): Action
    {
        return Action::make('删除')
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(SecretForm::delete())
            ->action(function (SecretService $secret_service) {
                try {
                    $secret_service->delete();
                    NotificationUtil::make(true, '已删除密钥');
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
