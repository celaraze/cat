<?php

namespace App\Filament\Resources\Information\SoftwareCategoryResource\Pages;

use App\Filament\Resources\Information\SoftwareCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = SoftwareCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
