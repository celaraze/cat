<?php

namespace App\Services;

use App\Models\AssetNumberTrack;
use Illuminate\Support\Collection;

class AssetNumberTrackService extends Service
{
    public function __construct(?AssetNumberTrack $asset_number_track = null)
    {
        $this->model = $asset_number_track ?? new AssetNumberTrack();

    }

    public static function pluckOptions(): Collection
    {
        return AssetNumberTrack::query()->pluck('asset_number', 'asset_number');
    }
}
