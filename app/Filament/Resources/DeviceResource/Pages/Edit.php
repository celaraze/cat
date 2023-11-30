<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Resources\DeviceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('返回')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    /**
     * 保存后返回上一个页面.
     *
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl;
    }
}
