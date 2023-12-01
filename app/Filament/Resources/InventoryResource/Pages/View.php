<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Actions\CommonAction;
use App\Filament\Actions\InventoryAction;
use App\Filament\Resources\InventoryResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = InventoryResource::class;

    protected function getActions(): array
    {
        return [
            InventoryAction::deleteInventory(),
            CommonAction::back($this->getResource()),
        ];
    }
}
