<?php

namespace App\Models;

use App\Services\AssetNumberTrackService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssetNumberTrack extends Model
{
    use HasFactory;

    /**
     * 模型到服务.
     */
    public function service(): AssetNumberTrackService
    {
        return new AssetNumberTrackService($this);
    }
}
