<?php

namespace App\Services;

use App\Models\VendorHasContact;

class VendorHasContactService
{
    public VendorHasContact $vendor_has_contact;

    public function __construct(?VendorHasContact $vendor_has_contact = null)
    {
        $this->vendor_has_contact = $vendor_has_contact ?? new VendorHasContact();
    }
}
