<?php

namespace App\Models\Information;

use App\Models\User;
use App\Services\Information\DeviceHasSoftwareService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceHasSoftware extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'information_device_has_software';

    /**
     * 一对一，软件记录有一个创建人.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 一对一，软件记录有一个软件.
     *
     * @return BelongsTo
     */
    public function software(): BelongsTo
    {
        return $this->belongsTo(Software::class, 'software_id', 'id');
    }

    /**
     * 一对一，软件记录有一个设备.
     *
     * @return BelongsTo
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    /**
     * 模型到服务.
     *
     * @return DeviceHasSoftwareService
     */
    public function service(): DeviceHasSoftwareService
    {
        return new DeviceHasSoftwareService($this);
    }
}
