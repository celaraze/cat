<?php

namespace App\Filament\Resources\FlowHasFormResource\Pages;

use App\Enums\FlowHasFormEnum;
use App\Filament\Resources\FlowHasFormResource;
use App\Models\FlowHasForm;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class Index extends ListRecords
{
    protected static string $resource = FlowHasFormResource::class;

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
                ->badge(FlowHasForm::query()->count())
                ->badgeColor('success'),
            '草稿' => Tab::make()
                ->badge(FlowHasForm::query()->where('status', 0)->count())
                ->badgeColor(FlowHasFormEnum::statusColor(0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 0)),
            '在途' => Tab::make()
                ->badge(FlowHasForm::query()->whereIn('status', [1, 3])->count())
                ->badgeColor(FlowHasFormEnum::statusColor(1))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [1, 3])),
            '驳回' => Tab::make()
                ->badge(FlowHasForm::query()->where('status', 2)->count())
                ->badgeColor(FlowHasFormEnum::statusColor(2))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 2)),
            '通过' => Tab::make()
                ->badge(FlowHasForm::query()->where('status', 4)->count())
                ->badgeColor(FlowHasFormEnum::statusColor(4))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 4)),
        ];
    }
}
