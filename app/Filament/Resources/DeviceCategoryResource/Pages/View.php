<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Resources\DeviceCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = DeviceCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
