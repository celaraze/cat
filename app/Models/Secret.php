<?php

namespace App\Models;

use App\Services\SecretService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Secret extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对多，一个密码有很多密码管理记录.
     */
    public function hasSecrets(): HasMany
    {
        return $this->hasMany(DeviceHasSecret::class, 'secret_id', 'id');
    }

    /**
     * 远程一对多，配件有很多个设备.
     */
    public function devices(): HasManyThrough
    {
        return $this->hasManyThrough(
            Device::class,  // 远程表
            DeviceHasSecret::class,   // 中间表
            'secret_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'device_id' // 中间表对远程表的关联字段
        );
    }

    /**
     * 模型到服务.
     */
    public function service(): SecretService
    {
        return new SecretService($this);
    }
}
