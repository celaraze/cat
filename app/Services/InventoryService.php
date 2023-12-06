<?php

namespace App\Services;

use App\Models\Inventory;
use Exception;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class InventoryService
{
    public Inventory $inventory;

    public function __construct(?Inventory $inventory = null)
    {
        $this->inventory = $inventory ?? new Inventory();
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
            $this->inventory->setAttribute('name', $data['name']);
            $this->inventory->setAttribute('class_name', $model);
            $this->inventory->setAttribute('user_id', auth()->id());
            $this->inventory->save();
            foreach ($model_ids as $model_id) {
                $model = $model::query()->where('id', $model_id)->first();
                $data = [
                    'asset_number' => $model->getAttribute('asset_number'),
                    'inventory_id' => $this->inventory->getKey(),
                    'user_id' => auth()->id(),
                ];
                $this->inventory->hasTracks()->create($data);
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
            $this->inventory->hasTracks()->delete();
            $this->inventory->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
