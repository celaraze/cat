<?php

namespace App\Filament\Resources\Information\PartCategoryResource\Pages;

use App\Filament\Resources\Information\PartCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = PartCategoryResource::class;

    protected function getActions(): array
    {
        return [
            Actions\EditAction::make()
                ->color('warning'),
            Actions\Action::make('返回')
                ->url($this->getResource()::getUrl('index')),
        ];
    }
}
