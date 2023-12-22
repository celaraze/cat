<?php

namespace App\Filament\Resources\FlowHasFormResource\Pages;

use App\Filament\Resources\FlowHasFormResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = FlowHasFormResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.view');
    }
}
