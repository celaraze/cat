<?php

namespace App\Models;

use App\Services\FootprintService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Footprint extends Model
{
    use HasFactory;

    /**
     * 模型到服务.
     */
    public function service(): FootprintService
    {
        return new FootprintService($this);
    }
}
