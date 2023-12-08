<?php

namespace App\Filament\Resources\ImportResource\Pages;

use App\Filament\Resources\ImportResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = ImportResource::class;

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
