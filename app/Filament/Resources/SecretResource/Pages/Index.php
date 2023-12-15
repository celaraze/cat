<?php

namespace App\Filament\Resources\SecretResource\Pages;

use App\Filament\Resources\SecretResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = SecretResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return '返回列表';
    }
}
