<?php

namespace App\Services;

use App\Models\PartCategory;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class PartCategoryService extends Service
{
    public function __construct(?PartCategory $part_category = null)
    {
        $this->model = $part_category ?? new PartCategory();
    }

    public static function pluckOptions(): Collection
    {
        return PartCategory::query()->pluck('name', 'id');
    }

    #[ArrayShape(['name' => 'string', 'creator_id' => 'int'])]
    public function create(array $data): PartCategory
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }

    public function delete(): ?bool
    {
        return $this->model->delete();
    }
}
