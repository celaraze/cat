<?php

namespace App\Services;

use App\Traits\Services\HasFootprint;
use Illuminate\Database\Eloquent\Model;

abstract class Service
{
    use HasFootprint;

    public Model $model;

    public function isDeleted(): bool
    {
        $has_deleted_at = $this->model->getConnection()->getSchemaBuilder()->hasColumn($this->model->getTable(), 'deleted_at');
        if ($has_deleted_at) {
            return ! ($this->model->getAttribute('deleted_at') == null);
        }

        return false;
    }
}
