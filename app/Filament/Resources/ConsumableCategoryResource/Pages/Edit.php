<?php

namespace App\Filament\Resources\ConsumableCategoryResource\Pages;

use App\Filament\Resources\ConsumableCategoryResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = ConsumableCategoryResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.edit');
    }
}
