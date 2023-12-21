<?php

namespace App\Services;

use App\Models\Brand;
use App\Traits\Services\HasFootprint;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class BrandService
{
    use HasFootprint;

    public Brand $model;

    public function __construct(?Brand $brand = null)
    {
        $this->model = $brand ?? new Brand();
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return Brand::query()->pluck('name', 'id');
    }

    /**
     * 创建.
     */
    #[ArrayShape(['name' => 'string'])]
    public function create(array $data): Brand
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->save();

        return $this->model;
    }

    /**
     * 删除.
     */
    public function delete(): void
    {
        $this->model->delete();
    }
}
