<?php

namespace App\Services;

use App\Models\OrganizationHasUser;
use App\Traits\Services\HasFootprint;

class OrganizationHasUserService
{
    use HasFootprint;

    public OrganizationHasUser $model;

    public function __construct(?OrganizationHasUser $organization_has_user = null)
    {
        $this->model = $organization_has_user ?? new OrganizationHasUser();
    }

    /**
     * 删除组织用户记录.
     */
    public function delete(): void
    {
        $this->model->delete();
    }
}
