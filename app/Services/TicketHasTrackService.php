<?php

namespace App\Services;

use App\Models\TicketHasTrack;
use JetBrains\PhpStorm\ArrayShape;

class TicketHasTrackService extends Service
{
    public function __construct(?TicketHasTrack $ticket_has_track = null)
    {
        return $this->model = $ticket_has_track ?? new TicketHasTrack();
    }

    #[ArrayShape([
        'ticket_id' => 'int',
        'comment' => 'string',
        'user_id' => 'int',
        'minutes' => 'int',
        'creator_id' => 'int',
    ])]
    public function create(array $data): void
    {
        $this->model->setAttribute('ticket_id', $data['ticket_id']);
        $this->model->setAttribute('comment', $data['comment']);
        $this->model->setAttribute('user_id', $data['user_id']);
        $this->model->setAttribute('minutes', $data['minutes']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();
    }
}
