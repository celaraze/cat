<?php

namespace App\Services;

use App\Models\PartCategory;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class PartCategoryService
{
    public PartCategory $part_category;

    public function __construct(?PartCategory $part_category = null)
    {
        $this->part_category = $part_category ?? new PartCategory();
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return PartCategory::query()->pluck('name', 'id');
    }

    /**
     * 新增配件分类.
     */
    #[ArrayShape(['name' => 'string'])]
    public function create(array $data): PartCategory
    {
        $this->part_category->setAttribute('name', $data['name']);
        $this->part_category->save();

        return $this->part_category;
    }

    /**
     * 删除配件分类.
     */
    public function delete(): ?bool
    {
        return $this->part_category->delete();
    }
}
