<?php

namespace App\Filament\Resources\Information\PartResource\Pages;

use App\Filament\Resources\Information\PartResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = PartResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
