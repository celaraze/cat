<?php

namespace App\Observers;

use App\Models\DeviceHasUser;

class DeviceHasUserObserver
{
    public function created(DeviceHasUser $device_has_user): void
    {
        $device_has_user->service()->footprint('create');
    }

    public function updated(DeviceHasUser $device_has_user): void
    {
        $device_has_user->service()->footprint('update');
    }

    public function deleted(DeviceHasUser $device_has_user): void
    {
        $device_has_user->service()->footprint('delete');
    }

    public function restored(DeviceHasUser $device_has_user): void
    {
        $device_has_user->service()->footprint('restore');
    }

    public function forceDeleted(DeviceHasUser $device_has_user): void
    {
        $device_has_user->service()->footprint('force_delete');
    }
}
