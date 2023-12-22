<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Filament\Resources\SoftwareResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = SoftwareResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.edit');
    }
}
