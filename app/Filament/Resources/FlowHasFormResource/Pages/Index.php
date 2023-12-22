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
        return __('cat/action.back');
    }

    public function getTabs(): array
    {
        return [
            __('cat/flow_has_form.status.all') => Tab::make()
                ->badge(FlowHasForm::query()->count())
                ->badgeColor('success'),
            __('cat/flow_has_form.status.draft') => Tab::make()
                ->badge(FlowHasForm::query()->where('status', 0)->count())
                ->badgeColor(FlowHasFormEnum::statusColor(0))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 0)),
            __('cat/flow_has_form.status.processing') => Tab::make()
                ->badge(FlowHasForm::query()->whereIn('status', [1, 3])->count())
                ->badgeColor(FlowHasFormEnum::statusColor(1))
                ->modifyQueryUsing(fn (Builder $query) => $query->whereIn('status', [1, 3])),
            __('cat/flow_has_form.status.rejected') => Tab::make()
                ->badge(FlowHasForm::query()->where('status', 2)->count())
                ->badgeColor(FlowHasFormEnum::statusColor(2))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 2)),
            __('cat/flow_has_form.status.approved') => Tab::make()
                ->badge(FlowHasForm::query()->where('status', 4)->count())
                ->badgeColor(FlowHasFormEnum::statusColor(4))
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', 4)),
        ];
    }
}
