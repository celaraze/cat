<?php

namespace App\Observers;

use App\Models\Vendor;

class VendorObserver
{
    public function created(Vendor $vendor): void
    {
        $vendor->service()->footprint('create');
    }

    public function updated(Vendor $vendor): void
    {
        $vendor->service()->footprint('update');
    }

    public function deleted(Vendor $vendor): void
    {
        $vendor->service()->footprint('delete');
    }

    public function restored(Vendor $vendor): void
    {
        $vendor->service()->footprint('restore');
    }

    public function forceDeleted(Vendor $vendor): void
    {
        $vendor->service()->footprint('force_delete');
    }
}
