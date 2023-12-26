<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Collection;

class RoleService extends Service
{
    public function __construct(?Role $role = null)
    {
        return $this->model = $role ?? new Role();
    }

    public static function pluckOptions(): Collection
    {
        $roles = Role::query();
        /* @var User $auth_user */
        $auth_user = auth()->user();
        if ($auth_user->is_super_admin()) {
            $roles = $roles->whereNotIn('id', [1]);
        }

        return $roles->pluck('name', 'id');
    }
}
