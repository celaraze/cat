<?php

namespace App\Services;

use App\Models\Ticket;
use JetBrains\PhpStorm\ArrayShape;

class TicketService extends Service
{
    public function __construct(?Ticket $ticket = null)
    {
        $this->model = $ticket ?? new Ticket();
    }

    #[ArrayShape([
        'asset_number' => 'string',
        'subject' => 'string',
        'description' => 'string',
        'category_id' => 'int',
        'status' => 'int',
        'priority' => 'int',
        'user_id' => 'int',
        'assignee_id' => 'int',
        'creator_id' => 'int',
    ])]
    public function create(array $data): Ticket
    {
        $this->model->setAttribute('asset_number', $data['asset_number']);
        $this->model->setAttribute('subject', $data['subject']);
        $this->model->setAttribute('description', $data['description']);
        $this->model->setAttribute('category_id', $data['category_id']);
        $this->model->setAttribute('status', 0);
        $this->model->setAttribute('priority', $data['priority']);
        $this->model->setAttribute('user_id', $data['user_id']);
        $this->model->setAttribute('assignee_id', 0);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }

    public function finish(): bool
    {
        $this->model->setAttribute('status', 2);

        return $this->model->save();
    }

    public function setAssignee(int $user_id): bool
    {
        $this->model->setAttribute('assignee_id', $user_id);
        $this->model->setAttribute('status', 1);

        return $this->model->save();
    }

    public function isSetAssignee(): bool
    {
        return $this->model->getAttribute('assignee_id');
    }

    public function minutePie(): array
    {
        $tracks = $this->model->tracks()
            ->with('user')
            ->selectRaw('user_id,SUM(minutes) as minutes')
            ->groupBy('user_id')
            ->get();
        $names = $tracks->pluck('user.name')->toArray();
        $minutes = $tracks->pluck('minutes')->toArray();

        return [
            'names' => $names,
            'minutes' => $minutes,
        ];
    }

    public function setTicketById(int $ticket_id): void
    {
        /* @var Ticket $ticket */
        $ticket = Ticket::query()->where('id', $ticket_id)->first();
        $this->model = $ticket;
    }

    public function isCompleted(): bool
    {
        return $this->model->getAttribute('status') == 2;
    }
}
