<?php

namespace App\Filament\Resources\AssetNumberRuleResource\Pages;

use App\Filament\Resources\AssetNumberRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = AssetNumberRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
