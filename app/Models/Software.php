<?php

namespace App\Models;

use App\Services\SoftwareService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Software extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'software';

    /**
     * 一对一，软件属于一个分类.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(SoftwareCategory::class, 'category_id', 'id');
    }

    /**
     * 一对一，软件属于一个品牌.
     *
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id', 'id');
    }

    /**
     * 一对多，软件有很多软件管理记录.
     *
     * @return HasMany
     */
    public function hasSoftware(): HasMany
    {
        return $this->hasMany(DeviceHasSoftware::class, 'software_id', 'id');
    }

    /**
     * 模型到服务.
     *
     * @return SoftwareService
     */
    public function service(): SoftwareService
    {
        return new SoftwareService($this);
    }
}
