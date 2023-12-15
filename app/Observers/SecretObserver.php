<?php

namespace App\Observers;

use App\Models\Secret;

class SecretObserver
{
    public function created(Secret $secret): void
    {
        $secret->service()->footprint('create');
    }

    public function updated(Secret $secret): void
    {
        $secret->service()->footprint('update');
    }

    public function deleted(Secret $secret): void
    {
        $secret->service()->footprint('delete');
    }

    public function restored(Secret $secret): void
    {
        $secret->service()->footprint('restore');
    }

    public function forceDeleted(Secret $secret): void
    {
        $secret->service()->footprint('force_delete');
    }
}
