<?php

namespace App\Observers;

use App\Models\DeviceCategory;

class DeviceCategoryObserver
{
    public function created(DeviceCategory $device_category): void
    {
        $device_category->service()->footprint('create');
    }

    public function updated(DeviceCategory $device_category): void
    {
        $device_category->service()->footprint('update');
    }

    public function deleted(DeviceCategory $device_category): void
    {
        $device_category->service()->footprint('delete');
    }

    public function restored(DeviceCategory $device_category): void
    {
        $device_category->service()->footprint('restore');
    }

    public function forceDeleted(DeviceCategory $device_category): void
    {
        $device_category->service()->footprint('force_delete');
    }
}
