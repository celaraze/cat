<?php

namespace App\Filament\Resources\FlowHasFormResource\Pages;

use App\Filament\Resources\FlowHasFormResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = FlowHasFormResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    public static function getNavigationLabel(): string
    {
        return '返回列表';
    }
}
