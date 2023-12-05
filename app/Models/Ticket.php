<?php

namespace App\Models;

use App\Services\TicketService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对一，工单所属分类.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id', 'id');
    }

    /**
     * 一对一，工单所属记录.
     */
    public function tracks(): HasMany
    {
        return $this->hasMany(TicketHasTrack::class, 'ticket_id', 'id');
    }

    /**
     * 一对一，工单所属创建者.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 一对一，工单所属处理人.
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id', 'id');
    }

    /**
     * 模型到服务.
     */
    public function service(): TicketService
    {
        return new TicketService($this);
    }
}
