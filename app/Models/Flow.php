<?php

namespace App\Models;

use App\Services\FlowService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Flow extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对多，一个工作流有多个节点.
     *
     * @return HasMany
     */
    public function nodes(): HasMany
    {
        return $this->hasMany(FlowHasNode::class, 'flow_id', 'id');
    }

    /**
     * 流程有活动的表单数量.
     *
     * @return int
     */
    public function activeForms(): int
    {
        return $this->forms()->whereNotIn('status', [3, 4])->count();
    }

    /**
     * 一对多，一个工作流有多个表单.
     *
     * @return HasMany
     */
    public function forms(): HasMany
    {
        return $this->hasMany(FlowHasForm::class, 'flow_id', 'id');
    }

    /**
     * 模型到服务.
     *
     * @return FlowService
     */
    public function service(): FlowService
    {
        return new FlowService($this);
    }
}
