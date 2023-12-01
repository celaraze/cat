<?php

namespace App\Models;

use App\Services\SoftwareCategoryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoftwareCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'software_categories';

    /**
     * 模型到服务.
     */
    public function service(): SoftwareCategoryService
    {
        return new SoftwareCategoryService($this);
    }
}
