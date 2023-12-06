<?php

namespace App\Services;

use App\Models\TicketCategory;
use Illuminate\Support\Collection;

class TicketCategoryService
{
    public TicketCategory $ticket_category;

    public function __construct(?TicketCategory $ticket_category = null)
    {
        $this->ticket_category = $ticket_category ?? new TicketCategory();
    }

    /**
     * 选单.
     */
    public static function pluckOptions(): Collection
    {
        return TicketCategory::query()->pluck('name', 'id');
    }

    /**
     * 创建.
     */
    public function create(array $data): TicketCategory
    {
        $this->ticket_category->setAttribute('name', $data['name']);
        $this->ticket_category->save();

        return $this->ticket_category;
    }
}
