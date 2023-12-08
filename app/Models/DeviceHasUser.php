<?php

namespace App\Models;

use App\Services\DeviceHasUserService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceHasUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'device_has_users';

    /**
     * 一对一，用户管理记录有一个用户.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 一对一，用户管理记录有一个设备.
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class, 'device_id', 'id');
    }

    public function service(): DeviceHasUserService
    {
        return new DeviceHasUserService($this);
    }
}
