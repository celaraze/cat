<?php

namespace App\Filament\Resources\AssetNumberRuleResource\Pages;

use App\Filament\Resources\AssetNumberRuleResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = AssetNumberRuleResource::class;

    public static function getNavigationLabel(): string
    {
        return '编辑';
    }
}
