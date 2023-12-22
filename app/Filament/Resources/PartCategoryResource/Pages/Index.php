<?php

namespace App\Filament\Resources\PartCategoryResource\Pages;

use App\Filament\Resources\PartCategoryResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = PartCategoryResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.back');
    }
}
