<?php

namespace App\Traits\Services;

use App\Models\Footprint;
use Illuminate\Database\Eloquent\Model;

trait HasFootprint
{
    public function footprint(string $action): void
    {
        /* @var Model $model */
        $model = $this->model;
        Footprint::query()->create([
            'action' => $action,
            'creator_id' => auth()->id() ?? 0,
            'model_class' => get_class($model),
            'model_id' => $model->getKey() ?? 0,
            'before' => json_encode($model->getOriginal()),
            'after' => json_encode($model->getDirty()),
        ]);
    }
}
