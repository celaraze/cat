<?php

namespace App\Filament\Resources\Information\PartCategoryResource\Pages;

use App\Filament\Resources\Information\PartCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = PartCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
