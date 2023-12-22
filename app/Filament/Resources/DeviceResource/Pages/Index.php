<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class Index extends ListRecords
{
    protected static string $resource = DeviceResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return __('cat.action.back');
    }

    public function getTabs(): array
    {
        return [
            __('cat.device.status.all') => Tab::make()
                ->badge(Device::query()->count())
                ->badgeColor('success'),
            __('cat.device.status.idle') => Tab::make()
                ->badge(Device::query()->where('status', 0)->count())
                ->badgeColor(AssetEnum::statusColor(0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 0)),
            __('cat.device.status.using') => Tab::make()
                ->badge(Device::query()->where('status', 1)->count())
                ->badgeColor(AssetEnum::statusColor(1))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 1)),
            __('cat.device.status.borrowing') => Tab::make()
                ->badge(Device::query()->where('status', 2)->count())
                ->badgeColor(AssetEnum::statusColor(2))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 2)),
            __('cat.device.status.retired') => Tab::make()
                ->badge(Device::query()->where('status', 3)->count())
                ->badgeColor(AssetEnum::statusColor(3))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 3)),
        ];
    }
}
