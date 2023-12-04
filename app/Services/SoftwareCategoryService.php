<?php

namespace App\Services;

use App\Models\SoftwareCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class SoftwareCategoryService
{
    public SoftwareCategory $software_category;

    public function __construct(SoftwareCategory $software_category = null)
    {
        $this->software_category = $software_category ?? new SoftwareCategory();
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return SoftwareCategory::query()->pluck('name', 'id');
    }

    /**
     * 通过名称获取模型.
     */
    public static function getModelByName(string $category_name): Model|Builder|null
    {
        return SoftwareCategory::query()->where('name', $category_name)->first();
    }

    /**
     * 创建设备分类.
     */
    #[ArrayShape(['name' => 'string'])]
    public function create(array $data): SoftwareCategory
    {
        $this->software_category->setAttribute('name', $data['name']);
        $this->software_category->save();

        return $this->software_category;
    }
}
