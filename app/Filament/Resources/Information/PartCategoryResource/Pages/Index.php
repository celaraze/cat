<?php

namespace App\Filament\Resources\Information\PartCategoryResource\Pages;

use App\Filament\Resources\Information\PartCategoryResource;
use App\Utils\TabUtil;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class Index extends ListRecords
{
    protected static string $resource = PartCategoryResource::class;

    protected static string $view = 'filament.resources.pages.list-records';

    protected static ?string $title = '';

    public function getTabs(): array
    {
        return TabUtil::partTabs();
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
