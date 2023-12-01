<?php

namespace App\Services;

use App\Models\AssetNumberTrack;

class AssetNumberTrackService
{
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
     * 新增资产编号记录.
     */
    public static function create(string $asset_number): void
    {
        AssetNumberTrack::query()->create([
            'asset_number' => $asset_number,
        ]);
    }
}
