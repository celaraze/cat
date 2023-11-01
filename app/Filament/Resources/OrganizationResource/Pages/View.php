<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Actions\CommonAction;
use App\Filament\Resources\OrganizationResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = OrganizationResource::class;

    protected function getActions(): array
    {
        return [CommonAction::back($this->getResource())];
    }
}
