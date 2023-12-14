<?php

namespace App\Observers;

use App\Models\FlowHasNode;

class FlowHasNodeObserver
{
    public function created(FlowHasNode $flow_has_node): void
    {
        $flow_has_node->service()->footprint('create');
    }

    public function updated(FlowHasNode $flow_has_node): void
    {
        $flow_has_node->service()->footprint('update');
    }

    public function deleted(FlowHasNode $flow_has_node): void
    {
        $flow_has_node->service()->footprint('delete');
    }

    public function restored(FlowHasNode $flow_has_node): void
    {
        $flow_has_node->service()->footprint('restore');
    }

    public function forceDeleted(FlowHasNode $flow_has_node): void
    {
        $flow_has_node->service()->footprint('force_delete');
    }
}
