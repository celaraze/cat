<?php

namespace App\Models;

use App\Services\ConsumableCategoryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsumableCategory extends Model
{
    use HasFactory, SoftDeletes;

    public function service(): ConsumableCategoryService
    {
        return new ConsumableCategoryService($this);
    }
}
