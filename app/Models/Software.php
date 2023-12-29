<?php

namespace App\Models;

use App\Services\SoftwareService;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Software extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'software';

    /**
     * 一对一，软件属于一个分类.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(SoftwareCategory::class, 'category_id', 'id');
    }

    /**
     * 一对一，软件属于一个品牌.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    /**
     * 一对一，软件有一个资产追踪.
     */
    public function assetNumberTrack(): HasOne
    {
        return $this->hasOne(AssetNumberTrack::class, 'asset_number', 'asset_number');
    }

    /**
     * 模型到服务.
     */
    public function service(): SoftwareService
    {
        return new SoftwareService($this);
    }

    /**
     * 已经使用的授权数量.
     */
    public function usedCount(): int
    {
        return $this->hasSoftware()->count();
    }

    /**
     * 一对多，软件有很多软件管理记录.
     */
    public function hasSoftware(): HasMany
    {
        return $this->hasMany(DeviceHasSoftware::class, 'software_id', 'id');
    }

    /**
     * 远程一对多，软件有很多个设备.
     */
    public function devices(): HasManyThrough
    {
        return $this->hasManyThrough(
            Device::class,  // 远程表
            DeviceHasSoftware::class,   // 中间表
            'software_id',    // 中间表对主表的关联字段
            'id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'device_id' // 中间表对远程表的关联字段
        );
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

    public function isRetiring(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->forms()->where('status', 0)->exists(),
        );
    }

    public function forms(): HasMany
    {
        return $this->hasMany(FlowHasForm::class, 'model_id', 'id')
            ->where('model_name', self::class);
    }
}
