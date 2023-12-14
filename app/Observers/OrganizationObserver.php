<?php

namespace App\Observers;

use App\Models\Organization;

class OrganizationObserver
{
    public function created(Organization $organization): void
    {
        $organization->service()->footprint('create');
    }

    public function updated(Organization $organization): void
    {
        $organization->service()->footprint('update');
    }

    public function deleted(Organization $organization): void
    {
        $organization->service()->footprint('delete');
    }

    public function restored(Organization $organization): void
    {
        $organization->service()->footprint('restore');
    }

    public function forceDeleted(Organization $organization): void
    {
        $organization->service()->footprint('force_delete');
    }
}
