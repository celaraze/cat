<?php

namespace App\Filament\Widgets;

use App\Services\TicketService;
use App\Utils\UrlUtil;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TicketHasTrackMinutePie extends ApexChartWidget
{
    protected static string $chartId = 'ticketHasTrackMinutePie';

    protected int|string|array $columnStart = 2;

    protected function getHeading(): ?string
    {
        return __('cat/ticket.widget.minute_pie');
    }

    protected function getOptions(): array
    {
        $ticket_id = UrlUtil::getRecordId();
        $ticket_service = new TicketService();
        $ticket_service->setTicketById((int) $ticket_id);
        $data = $ticket_service->minutePie();

        return [
            'chart' => [
                'type' => 'pie',
                'height' => 250,
            ],
            'series' => $data['minutes'],
            'labels' => $data['names'],
            'legend' => [
                'labels' => [
                    'fontFamily' => 'inherit',
                ],
            ],
        ];
    }
}
