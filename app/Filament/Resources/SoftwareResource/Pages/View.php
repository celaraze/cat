<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Filament\Resources\SoftwareResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = SoftwareResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
