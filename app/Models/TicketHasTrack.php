<?php

namespace App\Models;

use App\Services\TicketHasTrackService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketHasTrack extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * 一对一，工单记录所属工单.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    /**
     * 一对一，工单记录所属用户.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * 模型到服务.
     */
    public function service(): TicketHasTrackService
    {
        return new TicketHasTrackService($this);
    }
}
