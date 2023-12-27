<?php

namespace App\Models;

use App\Services\FlowService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flow extends Model
{
    use HasFactory, SoftDeletes;

    public function nodes(): HasMany
    {
        return $this->hasMany(FlowHasNode::class, 'flow_id', 'id');
    }

    public function forms(): HasManyThrough
    {
        return $this->hasManyThrough(
            FlowHasForm::class,  // 远程表
            FlowHasNode::class,   // 中间表
            'flow_id',    // 中间表对主表的关联字段
            'flow_has_node_id',   // 远程表对中间表的关联字段
            'id',   // 主表对中间表的关联字段
            'id' // 中间表对远程表的关联字段
        );
    }

    public function service(): FlowService
    {
        return new FlowService($this);
    }
}
