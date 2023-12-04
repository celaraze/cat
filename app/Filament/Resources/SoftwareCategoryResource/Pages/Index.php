<?php

namespace App\Filament\Resources\SoftwareCategoryResource\Pages;

use App\Filament\Resources\SoftwareCategoryResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = SoftwareCategoryResource::class;

    protected static string $view = 'filament.resources.pages.list-records';

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
