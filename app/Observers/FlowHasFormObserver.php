<?php

namespace App\Observers;

use App\Models\FlowHasForm;

class FlowHasFormObserver
{
    public function created(FlowHasForm $flow_has_form): void
    {
        $flow_has_form->service()->footprint('create');
    }

    public function updated(FlowHasForm $flow_has_form): void
    {
        $flow_has_form->service()->footprint('update');
    }

    public function deleted(FlowHasForm $flow_has_form): void
    {
        $flow_has_form->service()->footprint('delete');
    }

    public function restored(FlowHasForm $flow_has_form): void
    {
        $flow_has_form->service()->footprint('restore');
    }

    public function forceDeleted(FlowHasForm $flow_has_form): void
    {
        $flow_has_form->service()->footprint('force_delete');
    }
}
