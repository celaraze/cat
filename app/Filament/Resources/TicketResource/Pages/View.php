<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use App\Filament\Widgets\TicketHasTrackMinutePie;
use Filament\Resources\Pages\ViewRecord;

class View extends ViewRecord
{
    protected static string $resource = TicketResource::class;

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '详情';
    }

    protected function getFooterWidgets(): array
    {
        return [
            TicketHasTrackMinutePie::make(),
        ];
    }
}
