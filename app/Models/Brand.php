<?php

namespace App\Models;

use App\Services\BrandService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'brands';

    /**
     * 模型到服务.
     */
    public function service(): BrandService
    {
        return new BrandService($this);
    }
}
