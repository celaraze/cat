<?php

namespace App\Models;

use App\Services\FlowHasFormService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class FlowHasForm extends Model
{
    use HasFactory, SoftDeletes;

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id', 'id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id', 'id');
    }

    public function node(): belongsTo
    {
        return $this->belongsTo(FlowHasNode::class, 'flow_has_node_id', 'id');
    }

    public function service(): FlowHasFormService
    {
        return new FlowHasFormService($this);
    }
}
