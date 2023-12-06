<?php

namespace App\Filament\Resources\AssetNumberRuleResource\Pages;

use App\Filament\Resources\AssetNumberRuleResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = AssetNumberRuleResource::class;

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
