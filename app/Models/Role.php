<?php

namespace App\Models;

use App\Services\RoleService;

class Role extends \Spatie\Permission\Models\Role
{
    const SUPER_ADMIN_ID = 1;

    /**
     * 模型到服务.
     */
    public function service(): RoleService
    {
        return new RoleService($this);
    }
}
