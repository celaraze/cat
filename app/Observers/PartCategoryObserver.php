<?php

namespace App\Observers;

use App\Models\PartCategory;

class PartCategoryObserver
{
    public function created(PartCategory $part_category): void
    {
        $part_category->service()->footprint('create');
    }

    public function updated(PartCategory $part_category): void
    {
        $part_category->service()->footprint('update');
    }

    public function deleted(PartCategory $part_category): void
    {
        $part_category->service()->footprint('delete');
    }

    public function restored(PartCategory $part_category): void
    {
        $part_category->service()->footprint('restore');
    }

    public function forceDeleted(PartCategory $part_category): void
    {
        $part_category->service()->footprint('force_delete');
    }
}
