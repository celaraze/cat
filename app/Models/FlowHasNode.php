<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlowHasNode extends Model
{
    use HasFactory, SoftDeletes;

    public function forms(): HasMany
    {
        return $this->hasMany(FlowHasForm::class, 'flow_has_node_id', 'id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function previous()
    {
        $previous_order = $this->getAttribute('order') - 1;

        return $this->flow->nodes->where('order', $previous_order)->first() ?? null;
    }

    public function next()
    {
        $next_order = $this->getAttribute('order') + 1;

        return $this->flow->nodes->where('order', $next_order)->first() ?? null;
    }

    public function flow(): BelongsTo
    {
        return $this->belongsTo(Flow::class, 'flow_id', 'id');
    }
}
