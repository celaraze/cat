<?php

namespace App\Services;

use App\Models\PartCategory;
use App\Traits\Services\HasFootprint;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class PartCategoryService
{
    use HasFootprint;

    public PartCategory $model;

    public function __construct(?PartCategory $part_category = null)
    {
        $this->model = $part_category ?? new PartCategory();
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
        $this->model->setAttribute('name', $data['name']);
        $this->model->save();

        return $this->model;
    }

    /**
     * 删除配件分类.
     */
    public function delete(): ?bool
    {
        return $this->model->delete();
    }

    public function isDeleted(): bool
    {
        return ! ($this->model->getAttribute('deleted_at') == null);
    }
}
