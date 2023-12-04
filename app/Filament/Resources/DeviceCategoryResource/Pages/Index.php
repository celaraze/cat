<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Resources\DeviceCategoryResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = DeviceCategoryResource::class;

    protected static string $view = 'filament.resources.pages.list-records';

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
