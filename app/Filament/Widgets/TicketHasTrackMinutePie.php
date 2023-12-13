<?php

namespace App\Filament\Widgets;

use App\Services\TicketService;
use App\Utils\UrlUtil;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class TicketHasTrackMinutePie extends ApexChartWidget
{
    protected static string $chartId = 'ticketHasTrackMinutePie';

    protected static ?string $heading = '工时构成（分钟）';

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
