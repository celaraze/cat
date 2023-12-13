<?php

namespace App\Filament\Resources\PartResource\Pages;

use App\Filament\Resources\PartResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = PartResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '编辑';
    }
}
