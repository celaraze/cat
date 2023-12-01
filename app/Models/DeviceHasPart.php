<?php

namespace App\Models;

use App\Services\DeviceHasPartService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceHasPart extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'device_has_parts';

    /**
     * 一对一，配件记录有一个创建人.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 一对一，配件记录有一个配件.
     */
    public function part(): BelongsTo
    {
        return $this->belongsTo(Part::class, 'part_id', 'id');
    }

    /**
     * 一对一，配件记录有一个设备.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    /**
     * 模型到服务.
     */
    public function service(): DeviceHasPartService
    {
        return new DeviceHasPartService($this);
    }
}
