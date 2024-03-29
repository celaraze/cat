<?php

namespace App\Models;

use App\Services\InventoryHasTrackService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryHasTrack extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 模型到服务.
     */
    public function service(): InventoryHasTrackService
    {
        return new InventoryHasTrackService($this);
    }

    /**
     * 一对一，盘点追踪记录有一个盘点任务.
     */
    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id', 'id');
    }

    /**
     * 一对一，盘点任务有一个实际盘点人.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
