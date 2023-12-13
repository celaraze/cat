<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Resources\InventoryResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = InventoryResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
