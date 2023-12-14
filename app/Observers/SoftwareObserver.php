<?php

namespace App\Observers;

use App\Models\Software;

class SoftwareObserver
{
    public function created(Software $software): void
    {
        $software->service()->footprint('create');
    }

    public function updated(Software $software): void
    {
        $software->service()->footprint('update');
    }

    public function deleted(Software $software): void
    {
        $software->service()->footprint('delete');
    }

    public function restored(Software $software): void
    {
        $software->service()->footprint('restore');
    }

    public function forceDeleted(Software $software): void
    {
        $software->service()->footprint('force_delete');
    }
}
