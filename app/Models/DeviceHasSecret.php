<?php

namespace App\Models;

use App\Services\DeviceHasSecretService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceHasSecret extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对一，密钥记录有一个密钥.
     */
    public function secret(): BelongsTo
    {
        return $this->belongsTo(Secret::class, 'secret_id', 'id');
    }

    /**
     * 一对一，密钥记录有一个用户.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    /**
     * 模型到服务.
     */
    public function service(): DeviceHasSecretService
    {
        return new DeviceHasSecretService($this);
    }
}
