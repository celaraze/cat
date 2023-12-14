<?php

namespace App\Observers;

use App\Models\AssetNumberRule;

class AssetNumberRuleObserver
{
    public function created(AssetNumberRule $asset_number_rule): void
    {
        $asset_number_rule->service()->footprint('create');
    }

    public function updated(AssetNumberRule $asset_number_rule): void
    {
        $asset_number_rule->service()->footprint('update');
    }

    public function deleted(AssetNumberRule $asset_number_rule): void
    {
        $asset_number_rule->service()->footprint('delete');
    }

    public function restored(AssetNumberRule $asset_number_rule): void
    {
        $asset_number_rule->service()->footprint('restore');
    }

    public function forceDeleted(AssetNumberRule $asset_number_rule): void
    {
        $asset_number_rule->service()->footprint('force_delete');
    }
}
