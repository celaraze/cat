<?php

namespace App\Filament\Resources\TicketCategoryResource\Pages;

use App\Filament\Resources\TicketCategoryResource;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = TicketCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [

        ];
    }
}
