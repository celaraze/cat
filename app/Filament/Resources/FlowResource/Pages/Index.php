<?php

namespace App\Filament\Resources\FlowResource\Pages;

use App\Filament\Resources\FlowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = FlowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
