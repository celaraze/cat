<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Enums\TicketEnum;
use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Builder;

class Index extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return __('cat.action.back');
    }

    public function getTabs(): array
    {
        return [
            __('cat.ticket.status.all') => Tab::make()
                ->badge(Ticket::query()->count())
                ->badgeColor(Color::Slate),
            __('cat.ticket.status.idle') => Tab::make()
                ->badge(Ticket::query()->where('status', 0)->count())
                ->badgeColor(TicketEnum::statusColor(0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 0)),
            __('cat.ticket.status.processing') => Tab::make()
                ->badge(Ticket::query()->where('status', 1)->count())
                ->badgeColor(TicketEnum::statusColor(1))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 1)),
            __('cat.ticket.status.finished') => Tab::make()
                ->badge(Ticket::query()->where('status', 2)->count())
                ->badgeColor(TicketEnum::statusColor(2))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 2)),
        ];
    }
}
