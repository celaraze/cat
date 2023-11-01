<?php

namespace App\Models;

use App\Services\OrganizationHasUserService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationHasUser extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对一，组织用户记录有一个用户.
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 模型到服务.
     *
     * @return OrganizationHasUserService
     */
    public function service(): OrganizationHasUserService
    {
        return new OrganizationHasUserService($this);
    }
}
