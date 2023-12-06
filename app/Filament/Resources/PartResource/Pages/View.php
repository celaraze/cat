<?php

namespace App\Filament\Resources\PartResource\Pages;

use App\Filament\Resources\PartResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = PartResource::class;

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
