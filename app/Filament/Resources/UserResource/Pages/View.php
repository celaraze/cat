<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = UserResource::class;

    public static function getNavigationLabel(): string
    {
        return '详情';
    }
}
