<?php

namespace App\Services;

use App\Models\TicketCategory;
use App\Traits\HasFootprint;
use Illuminate\Support\Collection;

class TicketCategoryService
{
    use HasFootprint;

    public TicketCategory $model;

    public function __construct(?TicketCategory $ticket_category = null)
    {
        $this->model = $ticket_category ?? new TicketCategory();
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
        $this->model->setAttribute('name', $data['name']);
        $this->model->save();

        return $this->model;
    }

    /**
     * 删除工单分类.
     */
    public function delete(): ?bool
    {
        return $this->model->delete();
    }
}
