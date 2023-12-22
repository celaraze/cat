<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Resources\DeviceCategoryResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = DeviceCategoryResource::class;

    public static function getNavigationLabel(): string
    {
        return __('cat.action.edit');
    }
}
