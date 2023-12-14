<?php

namespace App\Observers;

use App\Models\DeviceHasPart;

class DeviceHasPartObserver
{
    public function created(DeviceHasPart $device_has_part): void
    {
        $device_has_part->service()->footprint('create');
    }

    public function updated(DeviceHasPart $device_has_part): void
    {
        $device_has_part->service()->footprint('update');
    }

    public function deleted(DeviceHasPart $device_has_part): void
    {
        $device_has_part->service()->footprint('delete');
    }

    public function restored(DeviceHasPart $device_has_part): void
    {
        $device_has_part->service()->footprint('restore');
    }

    public function forceDeleted(DeviceHasPart $device_has_part): void
    {
        $device_has_part->service()->footprint('force_delete');
    }
}
