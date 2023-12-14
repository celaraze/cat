<?php

namespace App\Observers;

use App\Models\Setting;

class SettingObserver
{
    public function created(Setting $setting): void
    {
        $setting->service()->footprint('create');
    }

    public function updated(Setting $setting): void
    {
        $setting->service()->footprint('update');
    }

    public function deleted(Setting $setting): void
    {
        $setting->service()->footprint('delete');
    }

    public function restored(Setting $setting): void
    {
        $setting->service()->footprint('restore');
    }

    public function forceDeleted(Setting $setting): void
    {
        $setting->service()->footprint('force_delete');
    }
}
