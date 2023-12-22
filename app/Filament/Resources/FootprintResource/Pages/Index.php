<?php

namespace App\Filament\Resources\FootprintResource\Pages;

use App\Filament\Resources\FootprintResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = FootprintResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat.action.back');
    }
}
