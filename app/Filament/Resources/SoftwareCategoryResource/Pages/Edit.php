<?php

namespace App\Filament\Resources\SoftwareCategoryResource\Pages;

use App\Filament\Resources\SoftwareCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = SoftwareCategoryResource::class;

    public static function getNavigationLabel(): string
    {
        return '编辑';
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
