<?php

namespace App\Models;

use App\Services\SoftwareCategoryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoftwareCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'software_categories';

    /**
     * 一对多，软件分类有很多软件.
     */
    public function software(): HasMany
    {
        return $this->hasMany(Software::class, 'category_id', 'id');
    }

    /**
     * 模型到服务.
     */
    public function service(): SoftwareCategoryService
    {
        return new SoftwareCategoryService($this);
    }
}
