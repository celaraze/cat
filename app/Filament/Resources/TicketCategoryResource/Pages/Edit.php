<?php

namespace App\Filament\Resources\TicketCategoryResource\Pages;

use App\Filament\Resources\TicketCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = TicketCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
