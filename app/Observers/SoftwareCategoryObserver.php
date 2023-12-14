<?php

namespace App\Observers;

use App\Models\SoftwareCategory;

class SoftwareCategoryObserver
{
    public function created(SoftwareCategory $software_category): void
    {
        $software_category->service()->footprint('create');
    }

    public function updated(SoftwareCategory $software_category): void
    {
        $software_category->service()->footprint('update');
    }

    public function deleted(SoftwareCategory $software_category): void
    {
        $software_category->service()->footprint('delete');
    }

    public function restored(SoftwareCategory $software_category): void
    {
        $software_category->service()->footprint('restore');
    }

    public function forceDeleted(SoftwareCategory $software_category): void
    {
        $software_category->service()->footprint('force_delete');
    }
}
