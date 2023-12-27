<?php

namespace App\Filament\Resources\FlowHasFormResource\Pages;

use App\Filament\Resources\FlowHasFormResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFlowHasForms extends ListRecords
{
    protected static string $resource = FlowHasFormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
