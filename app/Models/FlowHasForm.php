<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

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

    /**
     * 查询访问器，节点审批类型.
     */
    // TODO 这个可以从表格字段重写中完成
    protected function type(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->getAttribute('current_approve_user_id')) {
                    $name = '用户：'.User::query()
                            ->where('id', $this->getAttribute('current_approve_user_id'))
                            ->value('name');
                } elseif (! $this->getAttribute('current_approve_user_id') && ! $this->getAttribute('current_approve_role_id')) {
                    $name = '申请人';
                } else {
                    $name = '角色：'.Role::query()
                            ->where('id', $this->getAttribute('current_approve_role_id'))
                            ->value('name');
                }

                return $name;
            }
        );
    }

    /**
     * 查询访问器，状态文本.
     */
    protected function formStatusText(): Attribute
    {
        return Attribute::make(
            get: function () {
                $status = $this->getAttribute('status');

                return match ($status) {
                    0 => '待提交',
                    1, 2 => '审批中',
                    3 => '已驳回',
                    4 => '已通过'
                };
            }
        );
    }

    /**
     * 查询访问器，状态文本.
     */
    protected function nodeStatusText(): Attribute
    {
        return Attribute::make(
            get: function () {
                $status = $this->getAttribute('status');

                return match ($status) {
                    0 => '草稿',
                    1 => '同意',
                    2 => '退回',
                    3 => '驳回',
                    4 => '通过',
                };
            }
        );
    }
}
