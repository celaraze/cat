<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = DeviceResource::class;

    protected static string $view = 'filament.resources.pages.list-records';

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
