<?php

namespace App\Filament\Resources\VendorResource\Pages;

use App\Filament\Resources\VendorResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = VendorResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
