<?php

namespace App\Observers;

use App\Models\Brand;

class BrandObserver
{
    public function created(Brand $brand): void
    {
        $brand->service()->footprint('create');
    }

    public function updated(Brand $brand): void
    {
        $brand->service()->footprint('update');
    }

    public function deleted(Brand $brand): void
    {
        $brand->service()->footprint('delete');
    }

    public function restored(Brand $brand): void
    {
        $brand->service()->footprint('restore');
    }

    public function forceDeleted(Brand $brand): void
    {
        $brand->service()->footprint('force_delete');
    }
}
