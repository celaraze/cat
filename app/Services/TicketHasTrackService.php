<?php

namespace App\Services;

use App\Models\TicketHasTrack;
use App\Traits\Services\HasFootprint;
use JetBrains\PhpStorm\ArrayShape;

class TicketHasTrackService
{
    use HasFootprint;

    public TicketHasTrack $model;

    public function __construct(?TicketHasTrack $ticket_has_track = null)
    {
        return $this->model = $ticket_has_track ?? new TicketHasTrack();
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
        $this->model->setAttribute('ticket_id', $data['ticket_id']);
        $this->model->setAttribute('comment', $data['comment']);
        $this->model->setAttribute('user_id', $data['user_id']);
        $this->model->setAttribute('minutes', $data['minutes'] ?? 0);
        $this->model->save();
    }
}
