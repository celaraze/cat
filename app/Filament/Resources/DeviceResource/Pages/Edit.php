<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = DeviceResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '编辑';
    }
}
