<?php

namespace App\Services;

use App\Models\Ticket;

class TicketService
{
    public Ticket $ticket;

    public function __construct(?Ticket $ticket = null)
    {
        $this->ticket = $ticket ?? new Ticket();
    }

    /**
     * 创建.
     */
    public function create(array $data): Ticket
    {
        $this->ticket->setAttribute('asset_number', $data['asset_number']);
        $this->ticket->setAttribute('subject', $data['subject']);
        $this->ticket->setAttribute('description', $data['description']);
        $this->ticket->setAttribute('category_id', $data['category_id']);
        $this->ticket->setAttribute('status', 0);
        $this->ticket->setAttribute('priority', $data['priority']);
        $this->ticket->setAttribute('user_id', auth()->id());
        $this->ticket->setAttribute('assignee_id', 0);
        $this->ticket->save();

        return $this->ticket;
    }

    /**
     * 完成.
     */
    public function finish(): bool
    {
        $this->ticket->setAttribute('status', 2);

        return $this->ticket->save();
    }

    /**
     * 设置处理人.
     */
    public function setAssignee(int $user_id): bool
    {
        $this->ticket->setAttribute('assignee_id', $user_id);
        $this->ticket->setAttribute('status', 1);

        return $this->ticket->save();
    }

    /**
     * 获取工单是否有处理人.
     */
    public function isSetAssignee(): bool
    {
        return $this->ticket->getAttribute('assignee_id');
    }

    /**
     * 详情页的工时饼图数据.
     */
    public function minutePie(): array
    {
        $tracks = $this->ticket->tracks()
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
        $this->ticket = $ticket;
    }
}
