<?php

namespace App\Services;

use App\Models\VendorHasContact;
use JetBrains\PhpStorm\ArrayShape;

class VendorHasContactService
{
    public VendorHasContact $vendor_has_contact;

    public function __construct(VendorHasContact $vendor_has_contact = null)
    {
        $this->vendor_has_contact = $vendor_has_contact ?? new VendorHasContact();
    }

    /**
     * 创建供应商联系人.
     */
    #[ArrayShape(['vendor_id' => 'int', 'name' => 'string', 'phone_number' => 'string', 'email' => 'string'])]
    public function create(array $data): VendorHasContact
    {
        $this->vendor_has_contact->setAttribute('vendor_id', $data['vendor_id']);
        $this->vendor_has_contact->setAttribute('name', $data['name']);
        $this->vendor_has_contact->setAttribute('phone_number', $data['phone_number']);
        $this->vendor_has_contact->setAttribute('email', $data['email']);
        $this->vendor_has_contact->save();

        return $this->vendor_has_contact;
    }
}
