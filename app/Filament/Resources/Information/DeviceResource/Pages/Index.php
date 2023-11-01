<?php

namespace App\Filament\Resources\Information\DeviceResource\Pages;

use App\Filament\Resources\Information\DeviceResource;
use App\Utils\TabUtil;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = DeviceResource::class;

    protected static string $view = 'filament.resources.pages.list-records';

    protected static ?string $title = '';

    public function getTabs(): array
    {
        return TabUtil::deviceTabs();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
