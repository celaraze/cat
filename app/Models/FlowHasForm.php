<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlowHasForm extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对一，表单有一个工作流.
     */
    public function flow(): BelongsTo
    {
        return $this->belongsTo(Flow::class, 'flow_id', 'id');
    }

    /**
     * 一对一，表单有一个申请人.
     */
    public function applicantUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_user_id', 'id');
    }

    /**
     * 一对一，表单有一个审批人.
     */
    public function approveUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approve_user_id', 'id');
    }

    /**
     * 一对多，表单所有历史记录.
     */
    public function forms(): HasMany
    {
        return $this->hasMany($this, 'uuid', 'uuid');
    }

    /**
     * 一对一，表单有一个流程节点.
     */
    public function node(): HasOne
    {
        return $this->hasOne(FlowHasNode::class, 'id', 'node_id');
    }
}
