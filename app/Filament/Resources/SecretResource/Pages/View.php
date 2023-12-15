<?php

namespace App\Filament\Resources\SecretResource\Pages;

use App\Filament\Resources\SecretResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = SecretResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
