<?php

namespace App\Services;

use App\Models\PartCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PartCategoryService
{
    public PartCategory $part_category;

    public function __construct(PartCategory $part_category = null)
    {
        if ($part_category) {
            $this->part_category = $part_category;
        } else {
            $this->part_category = new PartCategory();
        }
    }

    /**
     * 选单.
     *
     * @return Collection
     */
    public static function pluckOptions(): Collection
    {
        return PartCategory::query()->pluck('name', 'id');
    }

    /**
     * 通过名称获取模型.
     *
     * @param string $category_name
     * @return Model|Builder|null
     */
    public static function getModelByName(string $category_name): Model|Builder|null
    {
        return PartCategory::query()->where('name', $category_name)->first();
    }

    /**
     * 新增配件分类.
     *
     * @param array $data
     * @return PartCategory
     */
    public function create(array $data): PartCategory
    {
        $this->part_category->setAttribute('name', $data['name']);
        $this->part_category->save();
        return $this->part_category;
    }

}
