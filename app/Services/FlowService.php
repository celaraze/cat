<?php

namespace App\Services;

use App\Models\Flow;
use Exception;
use Illuminate\Support\Facades\DB;

class FlowService extends Service
{
    public function __construct(?Flow $flow = null)
    {
        $this->model = $flow ?? new Flow();
    }

    public function create(array $data): Flow
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('slug', $data['slug']);
        $this->model->setAttribute('model_name', $data['model_name']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }

    /**
     * @throws Exception
     */
    public function delete(): void
    {
        DB::beginTransaction();
        try {
            $this->model->nodes()->delete();
            $this->model->delete();
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
