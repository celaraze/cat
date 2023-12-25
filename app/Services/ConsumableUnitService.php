<?php

namespace App\Services;

use App\Models\ConsumableUnit;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class ConsumableUnitService extends Service
{
    public function __construct($consumable_unit = null)
    {
        $this->model = $consumable_unit ?? new ConsumableUnit();
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return ConsumableUnit::query()
            ->pluck('name', 'id');
    }

    /**
     * 创建耗材单位.
     */
    #[ArrayShape(['name' => 'string'])]
    public function create(array $data): ConsumableUnit
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }

    /**
     * 删除耗材单位.
     */
    public function delete(): ?bool
    {
        return $this->model->delete();
    }
}
