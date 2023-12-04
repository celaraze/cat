<?php

namespace App\Filament\Resources\PartCategoryResource\Pages;

use App\Filament\Resources\PartCategoryResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = PartCategoryResource::class;

    protected static string $view = 'filament.resources.pages.list-records';

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
