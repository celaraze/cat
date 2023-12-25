<?php

namespace App\Services;

use App\Models\Permission;

class PermissionService extends Service
{
    public function __construct(?Permission $permission = null)
    {
        return $this->model = $permission ?? new Permission();
    }
}
