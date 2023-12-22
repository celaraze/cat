<?php

namespace App\Filament\Resources\TicketCategoryResource\Pages;

use App\Filament\Resources\TicketCategoryResource;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = TicketCategoryResource::class;

    public static function getNavigationLabel(): string
    {
        return __('cat.action.view');
    }
}
