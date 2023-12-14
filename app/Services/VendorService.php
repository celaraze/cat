<?php

namespace App\Services;

use App\Models\Vendor;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
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
    #[ArrayShape([
        'name' => 'string',
        'phone_number' => 'string',
        'email' => 'string',
    ])]
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

    /**
     * 删除.
     *
     * @throws Exception
     */
    public function delete(): void
    {
        try {
            DB::beginTransaction();
            $this->vendor->contacts()->delete();
            $this->vendor->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
