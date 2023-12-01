<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role;

class FlowHasNode extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对一，节点有一个工作流.
     */
    public function flow(): BelongsTo
    {
        return $this->belongsTo(Flow::class, 'flow_id', 'id');
    }

    /**
     * 一对一，节点属于一个父节点.
     */
    public function parentNode(): BelongsTo
    {
        return $this->belongsTo($this, 'parent_node_id', 'id');
    }

    /**
     * 一对一，节点有一个子节点.
     */
    public function childNode(): HasOne
    {
        return $this->hasOne($this, 'parent_node_id', 'id');
    }

    /**
     * 查询访问器，节点审批类型.
     */
    protected function type(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->getAttribute('user_id')) {
                    $name = '用户：'.User::query()->where('id', $this->getAttribute('user_id'))->value('name');
                } elseif (! $this->getAttribute('user_id') && ! $this->getAttribute('role_id')) {
                    $name = '申请人';
                } else {
                    $name = '角色：'.Role::query()->where('id', $this->getAttribute('role_id'))->value('name');
                }

                return $name;
            }
        );
    }
}
