<?php

namespace App\Services;

use App\Models\Role;
use App\Traits\HasFootprint;
use Illuminate\Support\Collection;

class RoleService
{
    use HasFootprint;

    public Role $model;

    public function __construct(?Role $role = null)
    {
        return $this->model = $role ?? new Role();
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return Role::query()->pluck('name', 'id');
    }
}
