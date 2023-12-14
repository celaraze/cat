<?php

namespace App\Models;

use App\Services\RoleService;

class Role extends \Spatie\Permission\Models\Role
{
    /**
     * 模型到服务.
     */
    public function service(): RoleService
    {
        return new RoleService($this);
    }
}
