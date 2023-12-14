<?php

namespace App\Services;

use App\Models\VendorHasContact;
use App\Traits\HasFootprint;

class VendorHasContactService
{
    use HasFootprint;

    public VendorHasContact $model;

    public function __construct(?VendorHasContact $vendor_has_contact = null)
    {
        $this->model = $vendor_has_contact ?? new VendorHasContact();
    }

    /**
     * 创建.
     */
    public function create(array $data): bool
    {
        $this->model->setAttribute('vendor_id', $data['vendor_id']);
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('phone_number', $data['phone_number']);
        $this->model->setAttribute('email', $data['email']);
        $this->model->setAttribute('additional', json_encode($data['additional']));

        return $this->model->save();
    }

    /**
     * 删除.
     */
    public function delete(): ?bool
    {
        return $this->model->delete();
    }
}
