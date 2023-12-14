<?php

namespace App\Observers;

use App\Models\Role;

class RoleObserver
{
    public function created(Role $role): void
    {
        $role->service()->footprint('create');
    }

    public function updated(Role $role): void
    {
        $role->service()->footprint('update');
    }

    public function deleted(Role $role): void
    {
        $role->service()->footprint('delete');
    }

    public function restored(Role $role): void
    {
        $role->service()->footprint('restore');
    }

    public function forceDeleted(Role $role): void
    {
        $role->service()->footprint('force_delete');
    }
}
