<?php

namespace App\Observers;

use App\Models\Inventory;

class InventoryObserver
{
    public function created(Inventory $inventory): void
    {
        $inventory->service()->footprint('create');
    }

    public function updated(Inventory $inventory): void
    {
        $inventory->service()->footprint('update');
    }

    public function deleted(Inventory $inventory): void
    {
        $inventory->service()->footprint('delete');
    }

    public function restored(Inventory $inventory): void
    {
        $inventory->service()->footprint('restore');
    }

    public function forceDeleted(Inventory $inventory): void
    {
        $inventory->service()->footprint('force_delete');
    }
}
