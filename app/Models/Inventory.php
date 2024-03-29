<?php

namespace App\Models;

use App\Services\InventoryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 模型到服务.
     */
    public function service(): InventoryService
    {
        return new InventoryService($this);
    }

    /**
     * 一对多，一个盘点任务有很多追踪记录.
     */
    public function hasTracks(): HasMany
    {
        return $this->hasMany(InventoryHasTrack::class, 'inventory_id', 'id');
    }

    /**
     * 一对一，盘点有一个创建人.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
}
