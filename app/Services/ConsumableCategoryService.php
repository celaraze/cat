<?php

namespace App\Services;

use App\Models\ConsumableCategory;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class ConsumableCategoryService extends Service
{
    public function __construct($consumable_category = null)
    {
        $this->model = $consumable_category ?? new ConsumableCategory();
    }

    public static function pluckOptions(): Collection
    {
        return ConsumableCategory::query()
            ->pluck('name', 'id');
    }

    #[ArrayShape(['name' => 'string', 'creator_id' => 'int'])]
    public function create(array $data): ConsumableCategory
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
