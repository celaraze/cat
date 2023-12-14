<?php

namespace App\Services;

use App\Models\Inventory;
use App\Traits\HasFootprint;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class InventoryService
{
    use HasFootprint;

    public Inventory $model;

    public function __construct(?Inventory $inventory = null)
    {
        $this->model = $inventory ?? new Inventory();
    }

    /**
     * 创建盘点.
     *
     * @throws Exception
     */
    #[ArrayShape([
        'name' => 'string',
        'class_name' => 'string',
        'model_ids' => 'array',
    ])]
    public function create(array $data): void
    {
        try {
            DB::beginTransaction();
            $model = $data['class_name'];
            $model_ids = $data['model_ids'];
            if (! count($model_ids)) {
                $model_ids = $model::query()->pluck('id')->toArray();
            }
            $this->model->setAttribute('name', $data['name']);
            $this->model->setAttribute('class_name', $model);
            $this->model->setAttribute('operator_id', auth()->id());
            $this->model->save();
            foreach ($model_ids as $model_id) {
                $model = $model::query()->where('id', $model_id)->first();
                $data = [
                    'asset_number' => $model->getAttribute('asset_number'),
                    'inventory_id' => $this->model->getKey(),
                    'operator_id' => auth()->id(),
                ];
                $this->model->hasTracks()->create($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * 删除盘点及所有追踪.
     *
     * @throws Exception
     */
    public function delete(): void
    {
        try {
            DB::beginTransaction();
            $this->model->hasTracks()->delete();
            $this->model->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
