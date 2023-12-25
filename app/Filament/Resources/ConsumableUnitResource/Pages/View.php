<?php

namespace App\Filament\Resources\ConsumableUnitResource\Pages;

use App\Filament\Resources\ConsumableUnitResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = ConsumableUnitResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.view');
    }
}
