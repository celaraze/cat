<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\TicketResource;
use App\Models\Ticket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Carbon;

class TicketOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $days = collect(range(0, 29))->map(function ($day) {
            $date = Carbon::now()->subDays($day);
            $all = Ticket::query()
                ->whereDate('created_at', $date)
                ->count();
            $status_0 = Ticket::query()
                ->whereDate('created_at', $date)
                ->where('status', 0)
                ->count();
            $status_1 = Ticket::query()
                ->whereDate('created_at', $date)
                ->where('status', 1)
                ->count();
            $status_2 = Ticket::query()
                ->whereDate('created_at', $date)
                ->where('status', 2)
                ->count();

            return compact('all', 'status_0', 'status_1', 'status_2');
        })->reverse();

        return [
            Stat::make(__('cat/menu.ticket'), Ticket::query()->count())
                ->description(__('cat/ticket.widget.overview_status_all'))
                ->chart($days->pluck('all')->flatten()->toArray())
                ->url(TicketResource::getUrl('index')),
            Stat::make(__('cat/menu.ticket'), Ticket::query()->where('status', 0)->count())
                ->description(__('cat/ticket.widget.overview_status_0'))
                ->chart($days->pluck('status_0')->flatten()->toArray())
                ->descriptionColor('gray'),
            Stat::make(__('cat/menu.ticket'), Ticket::query()->where('status', 1)->count())
                ->description(__('cat/ticket.widget.overview_status_1'))
                ->chart($days->pluck('status_1')->flatten()->toArray())
                ->descriptionColor('warning'),
            Stat::make(__('cat/menu.ticket'), Ticket::query()->where('status', 2)->count())
                ->description(__('cat/ticket.widget.overview_status_2'))
                ->chart($days->pluck('status_2')->flatten()->toArray())
                ->descriptionColor('success'),
        ];
    }
}
