<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AssetOverview;
use App\Filament\Widgets\TicketOverview;
use Filament\Pages\Page;

class DashBoard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-chart-pie';

    protected static string $view = 'filament.pages.dash-board';

    protected static ?string $navigationLabel = '总览';

    protected ?string $heading = '';

    protected function getHeaderWidgets(): array
    {
        return [
            AssetOverview::make(),
            TicketOverview::make(),
        ];
    }
}
