<?php

namespace App\Observers;

use App\Models\AssetNumberTrack;

class AssetNumberTrackObserver
{
    public function created(AssetNumberTrack $asset_number_track): void
    {
        $asset_number_track->service()->footprint('create');
    }

    public function updated(AssetNumberTrack $asset_number_track): void
    {
        $asset_number_track->service()->footprint('update');
    }

    public function deleted(AssetNumberTrack $asset_number_track): void
    {
        $asset_number_track->service()->footprint('delete');
    }

    public function restored(AssetNumberTrack $asset_number_track): void
    {
        $asset_number_track->service()->footprint('restore');
    }

    public function forceDeleted(AssetNumberTrack $asset_number_track): void
    {
        $asset_number_track->service()->footprint('force_delete');
    }
}
