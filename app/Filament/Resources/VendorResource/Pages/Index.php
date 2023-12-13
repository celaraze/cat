<?php

namespace App\Filament\Resources\VendorResource\Pages;

use App\Filament\Resources\VendorResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = VendorResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return '返回列表';
    }
}
