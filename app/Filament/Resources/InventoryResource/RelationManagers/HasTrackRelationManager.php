<?php

namespace App\Filament\Resources\InventoryResource\RelationManagers;

use App\Filament\Actions\InventoryAction;
use App\Models\InventoryHasTrack;
use App\Utils\InventoryUtil;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class HasTrackRelationManager extends RelationManager
{
    protected static string $relationship = 'hasTracks';

    protected static ?string $title = '明细';

    protected static ?string $icon = 'heroicon-m-cpu-chip';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('asset_number')
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('check')
                    ->formatStateUsing(function (string $state) {
                        return InventoryUtil::mapper($state);
                    })
                    ->label('状态'),
                Tables\Columns\TextColumn::make('comment')
                    ->label('备忘'),
            ])
            ->filters([
                //
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
