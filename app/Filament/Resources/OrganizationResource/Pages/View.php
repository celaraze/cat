<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = OrganizationResource::class;

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return __('cat.action.view');
    }
}
