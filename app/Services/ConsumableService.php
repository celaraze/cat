<?php

namespace App\Services;

use App\Models\Consumable;
use App\Models\Flow;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class ConsumableService extends Service
{
    public function __construct($consumable = null)
    {
        $this->model = $consumable ?? new Consumable();
    }

    public static function isSetRetireFlow(): bool
    {
        /* @var Flow $flow */
        $flow = self::getRetireFlow();

        return $flow?->nodes()->count() ?? false;
    }

    public static function getRetireFlow(): Builder|Model|null
    {
        return Flow::query()
            ->where('slug', 'retire_flow')
            ->where('model_name', Consumable::class)
            ->first();
    }

    /**
     * @throws Exception
     */
    #[ArrayShape([
        'name' => 'string',
        'category_id' => 'int',
        'brand_id' => 'int',
        'unit_id' => 'int',
        'specification' => 'string',
        'description' => 'string',
        'image' => 'string',
        'additional' => 'string',
        'status' => 'int',
        'creator_id' => 'int',
    ])]
    public function create(array $data): void
    {
        // 开始事务
        DB::beginTransaction();
        try {
            $this->model->setAttribute('name', $data['name']);
            $this->model->setAttribute('category_id', $data['category_id']);
            $this->model->setAttribute('brand_id', $data['brand_id']);
            $this->model->setAttribute('unit_id', $data['unit_id']);
            $this->model->setAttribute('specification', $data['specification']);
            $this->model->setAttribute('description', $data['description']);
            $this->model->setAttribute('image', $data['image']);
            $this->model->setAttribute('additional', json_encode($data['additional']));
            $this->model->setAttribute('status', $data['status']);
            $this->model->setAttribute('creator_id', $data['creator_id']);
            $this->model->save();
            // 写入事务
            DB::commit();
        } catch (Exception $exception) {
            // 回滚事务
            DB::rollBack();
            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    public function retire(): void
    {
        DB::beginTransaction();
        try {
            $this->model->tracks()->delete();
            $this->model->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }

    public function isRetired(): bool
    {
        return $this->model->getAttribute('status') == 3;
    }
}
