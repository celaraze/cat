<?php

namespace App\Filament\Resources\ConsumableUnitResource\Pages;

use App\Filament\Resources\ConsumableUnitResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = ConsumableUnitResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.edit');
    }
}
