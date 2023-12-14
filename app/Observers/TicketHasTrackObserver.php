<?php

namespace App\Observers;

use App\Models\TicketHasTrack;

class TicketHasTrackObserver
{
    public function created(TicketHasTrack $ticket_has_track): void
    {
        $ticket_has_track->service()->footprint('create');
    }

    public function updated(TicketHasTrack $ticket_has_track): void
    {
        $ticket_has_track->service()->footprint('update');
    }

    public function deleted(TicketHasTrack $ticket_has_track): void
    {
        $ticket_has_track->service()->footprint('delete');
    }

    public function restored(TicketHasTrack $ticket_has_track): void
    {
        $ticket_has_track->service()->footprint('restore');
    }

    public function forceDeleted(TicketHasTrack $ticket_has_track): void
    {
        $ticket_has_track->service()->footprint('force_delete');
    }
}
