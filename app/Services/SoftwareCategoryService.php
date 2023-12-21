<?php

namespace App\Services;

use App\Models\SoftwareCategory;
use App\Traits\Services\HasFootprint;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class SoftwareCategoryService
{
    use HasFootprint;

    public SoftwareCategory $model;

    public function __construct(?SoftwareCategory $software_category = null)
    {
        $this->model = $software_category ?? new SoftwareCategory();
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
        $this->model->setAttribute('name', $data['name']);
        $this->model->save();

        return $this->model;
    }

    /**
     * 删除软件分类.
     */
    public function delete(): ?bool
    {
        return $this->model->delete();
    }
}
