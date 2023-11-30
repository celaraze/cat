<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class BrandService
{
    public Brand $brand;

    public function __construct(Brand $brand = null)
    {
        if ($brand) {
            $this->brand = $brand;
        } else {
            $this->brand = new Brand();
        }
    }

    /**
     * 选单.
     *
     * @return Collection
     */
    public static function pluckOptions(): Collection
    {
        return Brand::query()->pluck('name', 'id');
    }

    /**
     * 通过名称获取模型.
     *
     * @param string $brand_name
     * @return Model|Builder|null
     */
    public static function getModelByName(string $brand_name): Model|Builder|null
    {
        return Brand::query()->where('name', $brand_name)->first();
    }

    /**
     * 创建信息资产品牌.
     *
     * @param array $data
     * @return Brand
     */
    public function create(array $data): Brand
    {
        $this->brand->setAttribute('name', $data['name']);
        $this->brand->save();
        return $this->brand;
    }
}
