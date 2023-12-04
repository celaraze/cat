<?php

namespace App\Filament\Resources\PartResource\Pages;

use App\Filament\Resources\PartResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = PartResource::class;

    protected static string $view = 'filament.resources.pages.list-records';

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
