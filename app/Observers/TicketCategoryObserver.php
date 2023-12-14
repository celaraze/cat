<?php

namespace App\Observers;

use App\Models\TicketCategory;

class TicketCategoryObserver
{
    public function created(TicketCategory $ticket_category): void
    {
        $ticket_category->service()->footprint('create');
    }

    public function updated(TicketCategory $ticket_category): void
    {
        $ticket_category->service()->footprint('update');
    }

    public function deleted(TicketCategory $ticket_category): void
    {
        $ticket_category->service()->footprint('delete');
    }

    public function restored(TicketCategory $ticket_category): void
    {
        $ticket_category->service()->footprint('restore');
    }

    public function forceDeleted(TicketCategory $ticket_category): void
    {
        $ticket_category->service()->footprint('force_delete');
    }
}
