<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceHasSecretForm;
use App\Models\Device;
use App\Models\DeviceHasSecret;
use App\Models\Secret;
use App\Services\DeviceHasSecretService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class DeviceHasSecretAction
{
    public static function batchDelete(): BulkAction
    {
        return BulkAction::make(__('cat/device_has_secret.action.batch_delete'))
            ->requiresConfirmation()
            ->color('danger')
            ->icon('heroicon-s-minus-circle')
            ->action(function (Collection $device_has_secrets) {
                $data = [
                    'creator_id' => auth()->id(),
                    'status' => 1,
                ];
                /* @var DeviceHasSecret $device_has_secret */
                foreach ($device_has_secrets as $device_has_secret) {
                    $device_has_secret->service()->delete($data);
                }
                NotificationUtil::make(true, __('cat/device_has_secret.action.batch_delete_success'));
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(): Action
    {
        return Action::make(__('cat/device_has_secret.action.delete'))
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
                    NotificationUtil::make(true, __('cat/device_has_secret.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function createFromSecret(?Model $out_secret = null): Action
    {
        /* @var Secret $out_secret */
        return Action::make(__('cat/device_has_secret.action.create_from_secret'))
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
                    NotificationUtil::make(true, __('cat/device_has_secret.action.create_from_secret_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function create(?Model $out_device = null): Action
    {
        return Action::make(__('cat/device_has_secret.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasSecretForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    foreach ($data['secret_ids'] as $secret_id) {
                        $data['secret_id'] = $secret_id;
                        $data['device_id'] = $device->getKey();
                        $data['creator_id'] = auth()->id();
                        $data['status'] = 0;
                        $device_has_secret_service = new DeviceHasSecretService();
                        $device_has_secret_service->create($data);
                    }
                    NotificationUtil::make(true, __('cat/device_has_secret.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function deleteFromSecret(): Action
    {
        return Action::make(__('cat/device_has_secret.action.delete_from_secret'))
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
                    NotificationUtil::make(true, __('cat/device_has_secret.action.delete_from_secret_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
