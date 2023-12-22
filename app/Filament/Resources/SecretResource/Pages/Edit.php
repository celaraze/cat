<?php

namespace App\Filament\Resources\SecretResource\Pages;

use App\Filament\Resources\SecretResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = SecretResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.edit');
    }
}
