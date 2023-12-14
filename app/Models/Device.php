<?php

namespace App\Models;

use App\Services\DeviceService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'devices';

    public function category(): BelongsTo
    {
        return $this->belongsTo(DeviceCategory::class, 'category_id', 'id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    public function assetNumberTrack(): HasOne
    {
        return $this->hasOne(AssetNumberTrack::class, 'asset_number', 'asset_number');
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            DeviceHasUser::class,
            'device_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function parts(): HasManyThrough
    {
        return $this->hasManyThrough(
            Part::class,
            DeviceHasPart::class,
            'device_id',
            'id',
            'id',
            'part_id'
        );
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'asset_number', 'asset_number');
    }

    public function software(): HasManyThrough
    {
        return $this->hasManyThrough(
            Software::class,  // 远程表
            DeviceHasSoftware::class,   // 中间表
            'device_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'software_id' // 中间表对远程表的关联字段
        );
    }

    public function hasParts(): HasMany
    {
        return $this->hasMany(DeviceHasPart::class, 'device_id', 'id');
    }

    public function hasSoftware(): HasMany
    {
        return $this->hasMany(DeviceHasSoftware::class, 'device_id', 'id');
    }

    public function hasUsers(): HasMany
    {
        return $this->hasMany(DeviceHasUser::class, 'device_id', 'id');
    }

    public function service(): DeviceService
    {
        return new DeviceService($this);
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
}
