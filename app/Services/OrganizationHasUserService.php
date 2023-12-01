<?php

namespace App\Services;

use App\Models\OrganizationHasUser;

class OrganizationHasUserService
{
    public OrganizationHasUser $organization_has_user;

    public function __construct(OrganizationHasUser $organization_has_user = null)
    {
        if ($organization_has_user) {
            $this->organization_has_user = $organization_has_user;
        } else {
            $this->organization_has_user = new OrganizationHasUser();
        }
    }

    /**
     * 删除组织用户记录.
     */
    public function delete(): void
    {
        $this->organization_has_user->delete();
    }
}
