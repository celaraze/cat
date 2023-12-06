<?php

namespace App\Filament\Resources\BrandResource\Pages;

use App\Filament\Resources\BrandResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = BrandResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    public static function getNavigationLabel(): string
    {
        return '返回列表';
    }
}
