<?php

namespace App\Observers;

use App\Models\DeviceHasSoftware;

class DeviceHasSoftwareObserver
{
    public function created(DeviceHasSoftware $device_has_software): void
    {
        $device_has_software->service()->footprint('create');
    }

    public function updated(DeviceHasSoftware $device_has_software): void
    {
        $device_has_software->service()->footprint('update');
    }

    public function deleted(DeviceHasSoftware $device_has_software): void
    {
        $device_has_software->service()->footprint('delete');
    }

    public function restored(DeviceHasSoftware $device_has_software): void
    {
        $device_has_software->service()->footprint('restore');
    }

    public function forceDeleted(DeviceHasSoftware $device_has_software): void
    {
        $device_has_software->service()->footprint('force_delete');
    }
}
