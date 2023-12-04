<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [

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
