<?php

namespace App\Services;

use App\Models\SoftwareCategory;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class SoftwareCategoryService
{
    public SoftwareCategory $software_category;

    public function __construct(?SoftwareCategory $software_category = null)
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
     * 创建设备分类.
     */
    #[ArrayShape(['name' => 'string'])]
    public function create(array $data): SoftwareCategory
    {
        $this->software_category->setAttribute('name', $data['name']);
        $this->software_category->save();

        return $this->software_category;
    }

    /**
     * 删除软件分类.
     */
    public function delete(): ?bool
    {
        return $this->software_category->delete();
    }
}
