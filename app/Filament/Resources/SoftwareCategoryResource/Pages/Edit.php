<?php

namespace App\Filament\Resources\SoftwareCategoryResource\Pages;

use App\Filament\Resources\SoftwareCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = SoftwareCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
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
