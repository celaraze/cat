<?php

namespace App\Models\Information;

use App\Services\Information\SoftwareService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Software extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'information_software';

    /**
     * 一对一，软件有一个分类.
     *
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(SoftwareCategory::class, 'id', 'category_id');
    }

    /**
     * 一对一，软件有一个品牌.
     *
     * @return HasOne
     */
    public function brand(): HasOne
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
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
