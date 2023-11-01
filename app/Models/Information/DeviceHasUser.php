<?php

namespace App\Models\Information;

use App\Models\User;
use App\Services\Information\DeviceHasUserService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeviceHasUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'information_device_has_users';

    /**
     * 一对一，用户管理记录有一个用户.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
