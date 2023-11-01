<?php

namespace App\Models;

use App\Services\OrganizationService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use SolutionForest\FilamentTree\Concern\ModelTree;

class Organization extends Model
{
    use HasFactory, ModelTree, SoftDeletes;

    protected $fillable = ['parent_id', 'name', 'order'];

    /**
     * 模型到服务.
     *
     * @return OrganizationService
     */
    public function service(): OrganizationService
    {
        return new OrganizationService($this);
    }

    /**
     * 一对多，组织有很多用户记录.
     *
     * @return HasMany
     */
    public function hasUsers(): HasMany
    {
        return $this->hasMany(OrganizationHasUser::class, 'organization_id', 'id');
    }
}
