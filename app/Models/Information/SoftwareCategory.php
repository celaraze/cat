<?php

namespace App\Models\Information;

use App\Services\Information\SoftwareCategoryService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SoftwareCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'information_software_categories';

    /**
     * 模型到服务.
     *
     * @return SoftwareCategoryService
     */
    public function service(): SoftwareCategoryService
    {
        return new SoftwareCategoryService($this);
    }
}
