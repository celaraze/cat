<?php

namespace App\Filament\Resources\ConsumableResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Resources\ConsumableResource;
use App\Models\Consumable;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class Index extends ListRecords
{
    protected static string $resource = ConsumableResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.back');
    }

    public function getTabs(): array
    {
        return [
            __('cat/consumable.status.all') => Tab::make()
                ->badge(Consumable::query()->count())
                ->badgeColor('success'),
            __('cat/consumable.status.normal') => Tab::make()
                ->badge(Consumable::query()->where('status', 4)->count())
                ->badgeColor(AssetEnum::statusColor(4))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 1)),
            __('cat/consumable.status.retired') => Tab::make()
                ->badge(Consumable::query()->where('status', 3)->count())
                ->badgeColor(AssetEnum::statusColor(3))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 3)),
        ];
    }
}
