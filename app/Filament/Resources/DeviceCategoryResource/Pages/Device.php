<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Actions\DeviceAction;
use App\Filament\Resources\DeviceCategoryResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Device extends ManageRelatedRecords
{
    protected static string $resource = DeviceCategoryResource::class;

    protected static string $relationship = 'devices';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = '设备';

    public static function getNavigationLabel(): string
    {
        return '设备';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('asset_number')
                    ->label('资产编号')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([

            ])
            ->actions([
                DeviceAction::toDevice(),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
