<?php

namespace App\Observers;

use App\Models\VendorHasContact;

class VendorHasContactObserver
{
    public function created(VendorHasContact $vendor_has_contact): void
    {
        $vendor_has_contact->service()->footprint('create');
    }

    public function updated(VendorHasContact $vendor_has_contact): void
    {
        $vendor_has_contact->service()->footprint('update');
    }

    public function deleted(VendorHasContact $vendor_has_contact): void
    {
        $vendor_has_contact->service()->footprint('delete');
    }

    public function restored(VendorHasContact $vendor_has_contact): void
    {
        $vendor_has_contact->service()->footprint('restore');
    }

    public function forceDeleted(VendorHasContact $vendor_has_contact): void
    {
        $vendor_has_contact->service()->footprint('force_delete');
    }
}
