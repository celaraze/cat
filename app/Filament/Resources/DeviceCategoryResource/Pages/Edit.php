<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Resources\DeviceCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = DeviceCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    /**
     * 保存后返回上一个页面.
     */
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl;
    }
}
