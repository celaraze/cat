<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Actions\InventoryAction;
use App\Filament\Resources\InventoryResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = InventoryResource::class;

    public static function getNavigationLabel(): string
    {
        return '详情';
    }

    protected function getActions(): array
    {
        return [
            // 放弃盘点
            InventoryAction::delete(),
        ];
    }
}
