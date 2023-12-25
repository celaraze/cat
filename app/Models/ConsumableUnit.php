<?php

namespace App\Models;

use App\Services\ConsumableUnitService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConsumableUnit extends Model
{
    use HasFactory, SoftDeletes;

    public function service(): ConsumableUnitService
    {
        return new ConsumableUnitService($this);
    }
}
