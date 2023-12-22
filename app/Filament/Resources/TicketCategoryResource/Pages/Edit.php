<?php

namespace App\Filament\Resources\TicketCategoryResource\Pages;

use App\Filament\Resources\TicketCategoryResource;
use Filament\Resources\Pages\EditRecord;

class Edit extends EditRecord
{
    protected static string $resource = TicketCategoryResource::class;

    public static function getNavigationLabel(): string
    {
        return __('cat/action.edit');
    }
}
