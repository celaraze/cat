<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

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
     */
    public static function pluckOptions(): Collection
    {
        return Brand::query()->pluck('name', 'id');
    }

    /**
     * 创建信息资产品牌.
     */
    #[ArrayShape(['name' => 'string'])]
    public function create(array $data): Brand
    {
        $this->brand->setAttribute('name', $data['name']);
        $this->brand->save();

        return $this->brand;
    }
}
