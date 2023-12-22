<?php

namespace App\Filament\Widgets;

use App\Services\FlowHasFormService;
use App\Utils\UrlUtil;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class FlowProgressChart extends ApexChartWidget
{
    protected static string $chartId = 'flowProgressChart';

    protected static ?string $pollingInterval = null;

    protected function getHeading(): ?string
    {
        return __('cat/widget.flow_progress_chart_heading');
    }

    protected function getOptions(): array
    {
        $flow_has_form_id = UrlUtil::getRecordId();
        $flow_has_form_service = new FlowHasFormService();
        $flow_has_form_service->setFlowHasFormById((int) $flow_has_form_id);
        $nodes = $flow_has_form_service->getNodes();

        return [
            'chart' => [
                'type' => 'heatmap',
                'height' => 80,
                'width' => '90%',
                'toolbar' => [
                    'show' => false,
                ],
            ],
            'series' => [
                ['data' => $nodes['id']],
            ],
            'xaxis' => [
                'type' => 'category',
                'labels' => [
                    'show' => true,
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
                'categories' => $nodes['name'],
            ],
            'yaxis' => [
                'labels' => [
                    'show' => false,
                ],
            ],
            'dataLabels' => [
                'enabled' => false,
            ],
            'colors' => ['#3B82F6'],
            'tooltip' => [
                'enabled' => false,
            ],
        ];
    }
}
