<?php

namespace App\Filament\Resources\Information\DeviceCategoryResource\Pages;

use App\Filament\Resources\Information\DeviceCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = DeviceCategoryResource::class;

    public static function canCreateAnother(): bool
    {
        return false;
    }

    /**
     * 保存后跳转至列表.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
