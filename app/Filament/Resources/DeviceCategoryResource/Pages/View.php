<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Resources\DeviceCategoryResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = DeviceCategoryResource::class;

    public static function getNavigationLabel(): string
    {
        return __('cat.action.view');
    }
}
