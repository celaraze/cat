<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $user->service()->footprint('create');
    }

    public function updated(User $user): void
    {
        $user->service()->footprint('update');
    }

    public function deleted(User $user): void
    {
        $user->service()->footprint('delete');
    }

    public function restored(User $user): void
    {
        $user->service()->footprint('restore');
    }

    public function forceDeleted(User $user): void
    {
        $user->service()->footprint('force_delete');
    }
}
