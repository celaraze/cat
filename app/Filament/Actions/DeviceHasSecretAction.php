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

    public static function create($model): Action
    {
        return Action::make(__('cat/device_has_secret.action.create'))
            ->slideOver()
            ->icon('heroicon-m-plus-circle')
            ->form(DeviceHasSecretForm::create($model))
            ->action(function (array $data) use ($model): void {
                try {
                    // 如果在设备页面创建，则获取设备 id
                    if ($model instanceof Device) {
                        $data['device_id'] = $model->getKey();
                    }
                    // 如果在密钥页面创建，则获取密钥 id，并创建一个单元素数组
                    // 因为下面处理时需要使用 foreach 循环，默认批量处理
                    if ($model instanceof Secret) {
                        $data['secret_ids'] = [$model->getKey()];
                    }
                    foreach ($data['secret_ids'] as $secret_id) {
                        $data['secret_id'] = $secret_id;
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
}
