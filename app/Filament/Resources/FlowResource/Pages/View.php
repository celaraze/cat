<?php

namespace App\Filament\Resources\FlowResource\Pages;

use App\Filament\Resources\FlowResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = FlowResource::class;

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
