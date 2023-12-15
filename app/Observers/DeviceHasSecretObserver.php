<?php

namespace App\Observers;

use App\Models\DeviceHasSecret;

class DeviceHasSecretObserver
{
    public function created(DeviceHasSecret $device_has_secret): void
    {
        $device_has_secret->service()->footprint('create');
    }

    public function updated(DeviceHasSecret $device_has_secret): void
    {
        $device_has_secret->service()->footprint('update');
    }

    public function deleted(DeviceHasSecret $device_has_secret): void
    {
        $device_has_secret->service()->footprint('delete');
    }

    public function restored(DeviceHasSecret $device_has_secret): void
    {
        $device_has_secret->service()->footprint('restore');
    }

    public function forceDeleted(DeviceHasSecret $device_has_secret): void
    {
        $device_has_secret->service()->footprint('force_delete');
    }
}
