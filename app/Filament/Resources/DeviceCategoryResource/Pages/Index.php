<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Resources\DeviceCategoryResource;
use App\Utils\TabUtil;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = DeviceCategoryResource::class;

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
