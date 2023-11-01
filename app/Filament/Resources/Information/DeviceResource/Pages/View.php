<?php

namespace App\Filament\Resources\Information\DeviceResource\Pages;

use App\Filament\Actions\CommonAction;
use App\Filament\Resources\Information\DeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = DeviceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning'),
            CommonAction::back($this->getResource()),
        ];
    }
}
