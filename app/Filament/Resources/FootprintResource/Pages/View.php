<?php

namespace App\Filament\Resources\FootprintResource\Pages;

use App\Filament\Resources\FootprintResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = FootprintResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat.action.view');
    }
}
