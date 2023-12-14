<?php

namespace App\Observers;

use App\Models\Flow;

class FlowObserver
{
    public function created(Flow $flow): void
    {
        $flow->service()->footprint('create');
    }

    public function updated(Flow $flow): void
    {
        $flow->service()->footprint('update');
    }

    public function deleted(Flow $flow): void
    {
        $flow->service()->footprint('delete');
    }

    public function restored(Flow $flow): void
    {
        $flow->service()->footprint('restore');
    }

    public function forceDeleted(Flow $flow): void
    {
        $flow->service()->footprint('force_delete');
    }
}
