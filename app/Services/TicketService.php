<?php

namespace App\Services;

use App\Models\Ticket;
use App\Traits\HasFootprint;

class TicketService
{
    use HasFootprint;

    public Ticket $model;

    public function __construct(?Ticket $ticket = null)
    {
        $this->model = $ticket ?? new Ticket();
    }

    /**
     * 创建.
     */
    public function create(array $data): Ticket
    {
        $this->model->setAttribute('asset_number', $data['asset_number']);
        $this->model->setAttribute('subject', $data['subject']);
        $this->model->setAttribute('description', $data['description']);
        $this->model->setAttribute('category_id', $data['category_id']);
        $this->model->setAttribute('status', 0);
        $this->model->setAttribute('priority', $data['priority']);
        $this->model->setAttribute('user_id', auth()->id());
        $this->model->setAttribute('assignee_id', 0);
        $this->model->save();

        return $this->model;
    }

    /**
     * 完成.
     */
    public function finish(): bool
    {
        $this->model->setAttribute('status', 2);

        return $this->model->save();
    }

    /**
     * 设置处理人.
     */
    public function setAssignee(int $user_id): bool
    {
        $this->model->setAttribute('assignee_id', $user_id);
        $this->model->setAttribute('status', 1);

        return $this->model->save();
    }

    /**
     * 获取工单是否有处理人.
     */
    public function isSetAssignee(): bool
    {
        return $this->model->getAttribute('assignee_id');
    }

    /**
     * 详情页的工时饼图数据.
     */
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
}
