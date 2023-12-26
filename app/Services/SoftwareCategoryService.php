<?php

namespace App\Services;

use App\Models\SoftwareCategory;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class SoftwareCategoryService extends Service
{
    public function __construct(?SoftwareCategory $software_category = null)
    {
        $this->model = $software_category ?? new SoftwareCategory();
    }

    public static function pluckOptions(): Collection
    {
        return SoftwareCategory::query()->pluck('name', 'id');
    }

    #[ArrayShape(['name' => 'string', 'creator_id' => 'int'])]
    public function create(array $data): SoftwareCategory
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
