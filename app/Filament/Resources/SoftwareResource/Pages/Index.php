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
        return '返回列表';
    }

    public function getTabs(): array
    {
        return [
            '全部' => Tab::make()
                ->badge(Software::query()->count())
                ->badgeColor('success'),
            '闲置' => Tab::make()
                ->badge(Software::query()->where('status', 0)->count())
                ->badgeColor(AssetEnum::statusColor(0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 0)),
            '使用' => Tab::make()
                ->badge(Software::query()->where('status', 1)->count())
                ->badgeColor(AssetEnum::statusColor(1))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 1)),
            '报废' => Tab::make()
                ->badge(Software::query()->where('status', 3)->count())
                ->badgeColor(AssetEnum::statusColor(3))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 3)),
        ];
    }
}
