<?php

namespace App\Models;

use App\Services\VendorHasContactService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class VendorHasContact extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对一，厂商联系人有一个厂商.
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'id', 'vendor_id');
    }

    /**
     * 访问器，额外信息.
     */
    public function additional(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => json_decode($value, true),
        );
    }

    /**
     * 模型到服务.
     */
    public function service(): VendorHasContactService
    {
        return new VendorHasContactService($this);
    }
}
