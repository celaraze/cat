<?php

namespace App\Models;

use App\Services\AssetNumberRuleService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssetNumberRule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 模型到服务.
     *
     * @return AssetNumberRuleService
     */
    public function service(): AssetNumberRuleService
    {
        return new AssetNumberRuleService($this);
    }
}
