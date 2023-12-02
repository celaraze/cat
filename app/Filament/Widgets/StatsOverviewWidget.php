<?php

namespace App\Filament\Widgets;

use App\Models\Device;
use App\Models\Part;
use App\Models\Software;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    use HasWidgetShield;

    protected static ?int $sort = 0;

    protected function getStats(): array
    {
        $device_count = Device::query()->count();
        $part_count = Part::query()->count();
        $software_count = Software::query()->count();

        $device_deleted_count = Device::onlyTrashed()->count();
        $part_deleted_count = Part::onlyTrashed()->count();
        $software_deleted_count = Software::onlyTrashed()->count();

        return [
            Stat::make('活跃设备总数', $device_count)
                ->description($device_deleted_count.' 已报废')
                ->color('danger'),
            Stat::make('活跃配件总数', $part_count)
                ->description($part_deleted_count.' 已报废')
                ->color('danger'),
            Stat::make('活跃软件总数', $software_count)
                ->description($software_deleted_count.' 已报废')
                ->color('danger'),
        ];
    }
}
