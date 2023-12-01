<?php

namespace App\Models;

use App\Services\PartService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parts';

    /**
     * 一对一，配件属于一个分类.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(PartCategory::class, 'category_id', 'id');
    }

    /**
     * 一对一，配件有一个品牌.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    /**
     * 模型到服务.
     */
    public function service(): PartService
    {
        return new PartService($this);
    }

    /**
     * 一对多，配件有很多配件管理记录.
     */
    public function hasParts(): HasMany
    {
        return $this->hasMany(DeviceHasPart::class, 'part_id', 'id');
    }
}
