<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Resources\DeviceCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class Create extends CreateRecord
{
    protected static string $resource = DeviceCategoryResource::class;

    /**
     * 保存后跳转至列表.
     */
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
