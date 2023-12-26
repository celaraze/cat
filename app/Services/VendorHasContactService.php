<?php

namespace App\Services;

use App\Models\VendorHasContact;
use JetBrains\PhpStorm\ArrayShape;

class VendorHasContactService extends Service
{
    public function __construct(?VendorHasContact $vendor_has_contact = null)
    {
        $this->model = $vendor_has_contact ?? new VendorHasContact();
    }

    #[ArrayShape([
        'vendor_id' => 'int',
        'name' => 'string',
        'phone_number' => 'string',
        'email' => 'string',
        'additional' => 'array',
        'creator_id' => 'int',
    ])]
    public function create(array $data): bool
    {
        $this->model->setAttribute('vendor_id', $data['vendor_id']);
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('phone_number', $data['phone_number']);
        $this->model->setAttribute('email', $data['email']);
        $this->model->setAttribute('additional', json_encode($data['additional']));
        $this->model->setAttribute('creator_id', $data['creator_id']);

        return $this->model->save();
    }

    public function delete(): ?bool
    {
        return $this->model->delete();
    }
}
