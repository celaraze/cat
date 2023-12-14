<?php

namespace App\Services;

use App\Models\Permission;
use App\Traits\HasFootprint;

class PermissionService
{
    use HasFootprint;

    public Permission $model;

    public function __construct(?Permission $permission = null)
    {
        return $this->model = $permission ?? new Permission();
    }
}
