<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Enums\InventoryEnum;
use App\Filament\Actions\InventoryHasTrackAction;
use App\Filament\Resources\InventoryResource;
use App\Models\InventoryHasTrack;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class HasTrack extends ManageRelatedRecords
{
    protected static string $resource = InventoryResource::class;

    protected static string $relationship = 'hasTracks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.inventory_has_track');
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.inventory_has_track');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/inventory_has_track.asset_number')),
                Tables\Columns\TextColumn::make('check')
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(function (string $state) {
                        return InventoryEnum::checkText($state);
                    })
                    ->label(__('cat/inventory_has_track.check')),
                Tables\Columns\TextColumn::make('comment')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/inventory_has_track.comment')),
                Tables\Columns\TextColumn::make('creator.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/inventory_has_track.creator')),
            ])
            ->filters([

            ])
            ->headerActions([

            ])
            ->actions([
                InventoryHasTrackAction::check()
                    ->visible(function (InventoryHasTrack $inventory_has_track) {
                        $can = auth()->user()->can('check_inventory');

                        return $can && ! $inventory_has_track->service()->isChecked();
                    }),
            ])
            ->bulkActions([

            ]);
    }
}
