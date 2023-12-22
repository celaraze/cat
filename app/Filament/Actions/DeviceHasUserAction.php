<?php

namespace App\Filament\Actions;

use App\Filament\Forms\DeviceHasUserForm;
use App\Models\Device;
use App\Models\DeviceHasUser;
use App\Services\DeviceHasUserService;
use App\Utils\LogUtil;
use App\Utils\NotificationUtil;
use Exception;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Model;

class DeviceHasUserAction
{
    public static function create(?Model $out_device = null): Action
    {
        return Action::make(__('cat/device_has_user.action.create'))
            ->slideOver()
            ->icon('heroicon-s-user-plus')
            ->form(DeviceHasUserForm::create())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    $data['device_id'] = $device->getKey();
                    $data['creator_id'] = auth()->id();
                    $device_has_user_service = new DeviceHasUserService();
                    $device_has_user_service->create($data);
                    NotificationUtil::make(true, __('cat/device_has_user.action.create_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }

    public static function delete(?Model $out_device = null): Action
    {
        return Action::make(__('cat/device_has_user.action.delete'))
            ->slideOver()
            ->icon('heroicon-s-user-minus')
            ->form(DeviceHasUserForm::delete())
            ->action(function (array $data, Device $device) use ($out_device): void {
                try {
                    if ($out_device) {
                        $device = $out_device;
                    }
                    $data['device_id'] = $device->getKey();
                    /* @var DeviceHasUser $device_has_user */
                    $device_has_user = $device->hasUsers()->first();
                    $device_has_user_service = $device_has_user->service();
                    $device_has_user_service->delete($data);
                    NotificationUtil::make(true, __('cat/device_has_user.action.delete_success'));
                } catch (Exception $exception) {
                    LogUtil::error($exception);
                    NotificationUtil::make(false, $exception);
                }
            })
            ->closeModalByClickingAway(false);
    }
}
