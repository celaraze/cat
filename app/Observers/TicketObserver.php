<?php

namespace App\Observers;

use App\Models\Ticket;

class TicketObserver
{
    public function created(Ticket $ticket): void
    {
        $ticket->service()->footprint('create');
    }

    public function updated(Ticket $ticket): void
    {
        $ticket->service()->footprint('update');
    }

    public function deleted(Ticket $ticket): void
    {
        $ticket->service()->footprint('delete');
    }

    public function restored(Ticket $ticket): void
    {
        $ticket->service()->footprint('restore');
    }

    public function forceDeleted(Ticket $ticket): void
    {
        $ticket->service()->footprint('force_delete');
    }
}
