<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = DeviceResource::class;

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
