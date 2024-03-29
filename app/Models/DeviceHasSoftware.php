<?php

namespace App\Models;

use App\Services\DeviceHasSoftwareService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceHasSoftware extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'device_has_software';

    /**
     * 一对一，软件记录有一个创建人.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    /**
     * 一对一，软件记录有一个软件.
     */
    public function software(): BelongsTo
    {
        return $this->belongsTo(Software::class, 'software_id', 'id');
    }

    /**
     * 一对一，软件记录有一个设备.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    /**
     * 模型到服务.
     */
    public function service(): DeviceHasSoftwareService
    {
        return new DeviceHasSoftwareService($this);
    }
}
