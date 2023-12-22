<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\AssetOverview;
use App\Filament\Widgets\TicketOverview;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-s-chart-pie';

    protected static string $view = 'cat.pages.dashboard';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.dashboard');
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AssetOverview::make(),
            TicketOverview::make(),
        ];
    }
}
