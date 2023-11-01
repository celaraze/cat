<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class RoleService
{
    public Role $role;

    public function __construct($role = null)
    {
        if ($role) {
            $this->role = $role;
        } else {
            $this->role = new Role();
        }
    }

    /**
     * 选单.
     *
     * @return Collection
     */
    public static function pluckOptions(): Collection
    {
        return Role::query()->pluck('name', 'id');
    }
}
