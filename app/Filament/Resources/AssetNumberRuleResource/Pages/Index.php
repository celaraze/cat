<?php

namespace App\Filament\Resources\AssetNumberRuleResource\Pages;

use App\Filament\Resources\AssetNumberRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = AssetNumberRuleResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    public static function getNavigationLabel(): string
    {
        return '返回列表';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
