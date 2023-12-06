<?php

namespace App\Services;

use App\Models\Vendor;
use Illuminate\Database\Eloquent\Model;
use JetBrains\PhpStorm\ArrayShape;

class VendorService
{
    public Vendor $vendor;

    public function __construct(?Vendor $vendor = null)
    {
        $this->vendor = $vendor ?? new Vendor();
    }

    /**
     * 创建厂商联系人.
     */
    #[ArrayShape(['name' => 'string', 'phone_number' => 'string', 'email' => 'string'])]
    public function createHasContacts(array $data): Model
    {
        return $this->vendor->contacts()->create($data);
    }

    /**
     * 创建厂商.
     */
    #[ArrayShape([
        'name' => 'string',
        'address' => 'string',
        'public_phone_number' => 'string',
        'referrer' => 'string',
    ])]
    public function create(array $data): Vendor
    {
        $this->vendor->setAttribute('name', $data['name']);
        $this->vendor->setAttribute('address', $data['address']);
        $this->vendor->setAttribute('public_phone_number', $data['public_phone_number']);
        $this->vendor->setAttribute('referrer', $data['referrer']);
        $this->vendor->save();

        return $this->vendor;
    }
}
