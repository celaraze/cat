<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

class RoleService
{
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
