<?php

namespace App\Observers;

use App\Models\InventoryHasTrack;

class InventoryHasTrackObserver
{
    public function created(InventoryHasTrack $inventory_has_track): void
    {
        $inventory_has_track->service()->footprint('create');
    }

    public function updated(InventoryHasTrack $inventory_has_track): void
    {
        $inventory_has_track->service()->footprint('update');
    }

    public function deleted(InventoryHasTrack $inventory_has_track): void
    {
        $inventory_has_track->service()->footprint('delete');
    }

    public function restored(InventoryHasTrack $inventory_has_track): void
    {
        $inventory_has_track->service()->footprint('restore');
    }

    public function forceDeleted(InventoryHasTrack $inventory_has_track): void
    {
        $inventory_has_track->service()->footprint('force_delete');
    }
}
