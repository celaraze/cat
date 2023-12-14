<?php

namespace App\Observers;

use App\Models\OrganizationHasUser;

class OrganizationHasUserObserver
{
    public function created(OrganizationHasUser $organization_has_user): void
    {
        $organization_has_user->service()->footprint('create');
    }

    public function updated(OrganizationHasUser $organization_has_user): void
    {
        $organization_has_user->service()->footprint('update');
    }

    public function deleted(OrganizationHasUser $organization_has_user): void
    {
        $organization_has_user->service()->footprint('delete');
    }

    public function restored(OrganizationHasUser $organization_has_user): void
    {
        $organization_has_user->service()->footprint('restore');
    }

    public function forceDeleted(OrganizationHasUser $organization_has_user): void
    {
        $organization_has_user->service()->footprint('force_delete');
    }
}
