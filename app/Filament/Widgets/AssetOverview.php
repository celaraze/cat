<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\DeviceResource;
use App\Filament\Resources\PartResource;
use App\Filament\Resources\SecretResource;
use App\Filament\Resources\SoftwareResource;
use App\Models\Device;
use App\Models\Part;
use App\Models\Secret;
use App\Models\Software;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AssetOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(__('cat.device'), Device::query()->count())
                ->description(Device::query()->where('status', 1)->count().__('cat.widget.using'))
                ->descriptionColor('success')
                ->url(DeviceResource::getUrl('index')),
            Stat::make(__('cat.part'), Part::query()->count())
                ->description(Part::query()->where('status', 1)->count().__('cat.widget.using'))
                ->descriptionColor('success')
                ->url(PartResource::getUrl('index')),
            Stat::make('cat.software', Software::query()->count())
                ->description(Software::query()->where('status', 1)->count().__('cat.widget.using'))
                ->descriptionColor('success')
                ->url(SoftwareResource::getUrl('index')),
            Stat::make('cat.secret', Secret::query()->count())
                ->description(Secret::query()->where('status', 1)->count().__('cat.widget.using'))
                ->descriptionColor('success')
                ->url(SecretResource::getUrl('index')),
        ];
    }
}
