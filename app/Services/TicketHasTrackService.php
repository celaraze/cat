<?php

namespace App\Services;

use App\Models\TicketHasTrack;
use JetBrains\PhpStorm\ArrayShape;

class TicketHasTrackService
{
    public TicketHasTrack $ticket_has_track;

    public function __construct(?TicketHasTrack $ticket_has_track = null)
    {
        return $this->ticket_has_track = $ticket_has_track ?? new TicketHasTrack();
    }

    /**
     * 创建工单记录.
     */
    #[ArrayShape([
        'ticket_id' => 'int',
        'comment' => 'string',
        'user_id' => 'int',
    ])]
    public function create(array $data): void
    {
        $this->ticket_has_track->setAttribute('ticket_id', $data['ticket_id']);
        $this->ticket_has_track->setAttribute('comment', $data['comment']);
        $this->ticket_has_track->setAttribute('user_id', $data['user_id']);
        $this->ticket_has_track->setAttribute('minutes', $data['minutes'] ?? 0);
        $this->ticket_has_track->save();
    }
}
