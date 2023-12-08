<?php

namespace App\Filament\Resources\TicketCategoryResource\Pages;

use App\Filament\Resources\TicketCategoryResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = TicketCategoryResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    public static function getNavigationLabel(): string
    {
        return '返回列表';
    }
}
