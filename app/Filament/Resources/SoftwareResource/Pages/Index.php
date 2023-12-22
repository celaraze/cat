<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Resources\SoftwareResource;
use App\Models\Software;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class Index extends ListRecords
{
    protected static string $resource = SoftwareResource::class;

    protected static ?string $navigationIcon = 'heroicon-m-arrow-uturn-left';

    protected ?string $heading = '';

    public static function getNavigationLabel(): string
    {
        return __('cat/action.back');
    }

    public function getTabs(): array
    {
        return [
            __('cat/software.status.all') => Tab::make()
                ->badge(Software::query()->count())
                ->badgeColor('success'),
            __('cat/software.status.idle') => Tab::make()
                ->badge(Software::query()->where('status', 0)->count())
                ->badgeColor(AssetEnum::statusColor(0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 0)),
            __('cat/software.status.using') => Tab::make()
                ->badge(Software::query()->where('status', 1)->count())
                ->badgeColor(AssetEnum::statusColor(1))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 1)),
            __('cat/software.status.retired') => Tab::make()
                ->badge(Software::query()->where('status', 3)->count())
                ->badgeColor(AssetEnum::statusColor(3))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 3)),
        ];
    }
}
