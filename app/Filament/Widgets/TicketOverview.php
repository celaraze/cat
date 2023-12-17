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
            Stat::make('工单', Ticket::query()->count())
                ->description('总数（近一个月趋势）')
                ->chart($days->pluck('all')->flatten()->toArray())
                ->url(TicketResource::getUrl('index')),
            Stat::make('工单', Ticket::query()->where('status', 0)->count())
                ->description('空闲（近一个月趋势）')
                ->chart($days->pluck('status_0')->flatten()->toArray())
                ->descriptionColor('gray'),
            Stat::make('工单', Ticket::query()->where('status', 1)->count())
                ->description('进行（近一个月趋势）')
                ->chart($days->pluck('status_1')->flatten()->toArray())
                ->descriptionColor('warning'),
            Stat::make('工单', Ticket::query()->where('status', 2)->count())
                ->description('完成（近一个月趋势）')
                ->chart($days->pluck('status_2')->flatten()->toArray())
                ->descriptionColor('success'),
        ];
    }
}
