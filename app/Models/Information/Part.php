<?php

namespace App\Models\Information;

use App\Services\Information\PartService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Part extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'information_parts';

    /**
     * 一对一，配件有一个分类.
     *
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(PartCategory::class, 'id', 'category_id');
    }

    /**
     * 一对一，配件有一个品牌.
     *
     * @return HasOne
     */
    public function brand(): HasOne
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    /**
     * 模型到服务.
     *
     * @return PartService
     */
    public function service(): PartService
    {
        return new PartService($this);
    }

    /**
     * 一对多，配件有很多配件管理记录.
     *
     * @return HasMany
     */
    public function hasParts(): HasMany
    {
        return $this->hasMany(DeviceHasPart::class, 'part_id', 'id');
    }
}
