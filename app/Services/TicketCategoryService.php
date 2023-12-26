<?php

namespace App\Services;

use App\Models\TicketCategory;
use Illuminate\Support\Collection;
use JetBrains\PhpStorm\ArrayShape;

class TicketCategoryService extends Service
{
    public function __construct(?TicketCategory $ticket_category = null)
    {
        $this->model = $ticket_category ?? new TicketCategory();
    }

    public static function pluckOptions(): Collection
    {
        return TicketCategory::query()->pluck('name', 'id');
    }

    #[ArrayShape(['name' => 'string', 'creator_id' => 'int'])]
    public function create(array $data): TicketCategory
    {
        $this->model->setAttribute('name', $data['name']);
        $this->model->setAttribute('creator_id', $data['creator_id']);
        $this->model->save();

        return $this->model;
    }

    public function delete(): ?bool
    {
        return $this->model->delete();
    }
}
