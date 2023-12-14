<?php

namespace App\Observers;

use App\Models\Part;

class PartObserver
{
    public function created(Part $part): void
    {
        $part->service()->footprint('create');
    }

    public function updated(Part $part): void
    {
        $part->service()->footprint('update');
    }

    public function deleted(Part $part): void
    {
        $part->service()->footprint('delete');
    }

    public function restored(Part $part): void
    {
        $part->service()->footprint('restore');
    }

    public function forceDeleted(Part $part): void
    {
        $part->service()->footprint('force_delete');
    }
}
