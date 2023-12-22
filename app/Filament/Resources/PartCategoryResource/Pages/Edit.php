<?php

namespace App\Filament\Resources\PartCategoryResource\Pages;

use App\Filament\Resources\PartCategoryResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = PartCategoryResource::class;

    public static function getNavigationLabel(): string
    {
        return __('cat.action.edit');
    }
}
