<?php

namespace App\Filament\Resources\InventoryResource\Pages;

use App\Filament\Actions\InventoryAction;
use App\Filament\Resources\InventoryResource;
use App\Models\InventoryHasTrack;
use App\Utils\InventoryUtil;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class HasTrack extends ManageRelatedRecords
{
    protected static string $resource = InventoryResource::class;

    protected static string $relationship = 'hasTracks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = '记录';

    public static function getNavigationLabel(): string
    {
        return '记录';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('check')
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(function (string $state) {
                        return InventoryUtil::mapper($state);
                    })
                    ->label('状态'),
                Tables\Columns\TextColumn::make('comment')
                    ->searchable()
                    ->toggleable()
                    ->label('备忘'),
            ])
            ->filters([

            ])
            ->headerActions([

            ])
            ->actions([
                InventoryAction::check()
                    ->visible(function (InventoryHasTrack $inventory_has_track) {
                        return ! $inventory_has_track->service()->isChecked();
                    }),
            ])
            ->bulkActions([

            ]);
    }
}
