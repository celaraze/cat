<?php

namespace App\Observers;

use App\Models\Device;

class DeviceObserver
{
    public function created(Device $device): void
    {
        $device->service()->footprint('create');
    }

    public function updated(Device $device): void
    {
        $device->service()->footprint('update');
    }

    public function deleted(Device $device): void
    {
        $device->service()->footprint('delete');
    }

    public function restored(Device $device): void
    {
        $device->service()->footprint('restore');
    }

    public function forceDeleted(Device $device): void
    {
        $device->service()->footprint('force_delete');
    }
}
