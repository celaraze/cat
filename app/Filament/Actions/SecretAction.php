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
    public static function viewToken(): Action
    {
        return Action::make(__('cat.action.view_token'))
            ->icon('heroicon-m-key')
            ->color('warning')
            ->requiresConfirmation()
            ->modalDescription(__('cat.action.view_token_description'))
            ->form(SecretForm::viewToken())
            ->action(function (array $data, Secret $secret) {
                try {
                    if (auth()->attempt(['email' => auth()->user()->email, 'password' => $data['password']])) {
                        NotificationUtil::make(true, __('cat.password').decrypt($secret->getAttribute('token')), true);
                    } else {
                        NotificationUtil::make(false, __('cat.action.view_token_failure'));
                    }
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }

    public static function createDeviceHasSecret(?Model $out_secret = null): Action
    {
        /* @var Secret $out_secret */
        return Action::make(__('cat.action.assign_device'))
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
                    NotificationUtil::make(true, __('cat.action.assign_device_success'));
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
            ->form(SecretForm::createOrEdit())
            ->action(function (array $data) {
                try {
                    $data['creator_id'] = auth()->id();
                    $secret_service = new SecretService();
                    $secret_service->create($data);
                    NotificationUtil::make(true, __('cat.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteDeviceHasSecret(): Action
    {
        return Action::make(__('cat.action.unassign_device'))
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
                    NotificationUtil::make(true, __('cat.action.unassign_device_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function retire(): Action
    {
        return Action::make(__('cat.action.retire'))
            ->icon('heroicon-m-trash')
            ->color('danger')
            ->requiresConfirmation()
            ->form(SecretForm::delete())
            ->action(function (Secret $secret) {
                try {
                    $secret->service()->retire();
                    NotificationUtil::make(true, __('cat.action.retire_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            });
    }
}
