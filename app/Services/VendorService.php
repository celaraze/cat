<?php

namespace App\Services;

use App\Models\Vendor;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class VendorService extends Service
{
    public function __construct(?Vendor $vendor = null)
    {
        $this->model = $vendor ?? new Vendor();
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
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('address', $data['address']);
        $this->model->setAttribute('public_phone_number', $data['public_phone_number']);
        $this->model->setAttribute('referrer', $data['referrer']);
        $this->model->save();

        return $this->model;
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
            $this->model->contacts()->delete();
            $this->model->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
