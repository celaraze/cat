<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = DeviceResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    public static function getNavigationLabel(): string
    {
        return '返回列表';
    }
}
