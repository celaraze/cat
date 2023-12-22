<?php

namespace App\Filament\Resources\SoftwareCategoryResource\Pages;

use App\Filament\Resources\SoftwareCategoryResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = SoftwareCategoryResource::class;

    public static function getNavigationLabel(): string
    {
        return __('cat/action.view');
    }
}
