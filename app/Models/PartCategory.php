<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'part_categories';

    /**
     * 一对多，配件分类有很多配件.
     */
    public function parts(): HasMany
    {
        return $this->hasMany(Part::class, 'category_id', 'id');
    }
}
