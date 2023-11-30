<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Filament\Resources\SoftwareResource;
use App\Utils\TabUtil;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = SoftwareResource::class;

    protected static string $view = 'filament.resources.pages.list-records';

    protected static ?string $title = '';

    public function getTabs(): array
    {
        return TabUtil::softwareTabs();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
