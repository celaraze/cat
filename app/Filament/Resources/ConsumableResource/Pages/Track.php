<?php

namespace App\Filament\Resources\ConsumableResource\Pages;

use App\Filament\Actions\ConsumableHasTrackAction;
use App\Filament\Resources\ConsumableResource;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Track extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = ConsumableResource::class;

    protected static string $relationship = 'tracks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.consumable_has_track');
    }

    public static function getNavigationBadge(): ?string
    {
        return self::queryRecord()->getAttribute('quantity');
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.consumable_has_track');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable_has_track.created_at')),
                Tables\Columns\TextColumn::make('creator.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable_has_track.creator')),
                Tables\Columns\TextColumn::make('quantity')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color(function ($state) {
                        return $state > 0 ? 'success' : 'danger';
                    })
                    ->label(__('cat/consumable_has_track.quantity')),
                Tables\Columns\TextColumn::make('comment')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable_has_track.comment')),
            ])
            ->filters([

            ])
            ->headerActions([
                // 创建
                ConsumableHasTrackAction::create($this->getOwnerRecord())
                    ->visible(function () {
                        return auth()->user()->can('create_has_track_consumable');
                    }),
            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
