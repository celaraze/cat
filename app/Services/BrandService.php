<?php

namespace App\Services;

use App\Models\Brand;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class BrandService extends Service
{
    public function __construct(?Brand $brand = null)
    {
        $this->model = $brand ?? new Brand();
    }

    public static function pluckOptions(): Collection
    {
        return Brand::query()->pluck('name', 'id');
    }

    #[ArrayShape(['name' => 'string', 'creator_id' => 'int'])]
    public function create(array $data): Brand
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }

    public function delete(): void
    {
        $this->model->delete();
    }
}
