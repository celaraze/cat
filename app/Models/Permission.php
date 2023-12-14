<?php

namespace App\Models;

use App\Services\PermissionService;

class Permission extends \Spatie\Permission\Models\Permission
{
    /**
     * 模型到服务.
     */
    public function service(): PermissionService
    {
        return new PermissionService($this);
    }
}
