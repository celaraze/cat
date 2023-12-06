<?php

namespace App\Services;

use App\Models\AssetNumberTrack;
use Illuminate\Support\Collection;

class AssetNumberTrackService
{
    public AssetNumberTrack $asset_number_track;

    public function __construct(?AssetNumberTrack $asset_number_track = null)
    {
        $this->asset_number_track = $asset_number_track ?? new AssetNumberTrack();

    }

    /**
     * 判断资产编号是否在记录清单中.
     */
    public static function isExist(string $asset_number): bool
    {
        $asset_number_track = AssetNumberTrack::query()
            ->where('asset_number', $asset_number)->first();
        if ($asset_number_track) {
            return true;
        }

        return false;
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return AssetNumberTrack::query()->pluck('asset_number', 'asset_number');
    }
}
