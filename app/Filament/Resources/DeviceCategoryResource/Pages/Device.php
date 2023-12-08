<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Actions\DeviceCategoryAction;
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
                    ->searchable()
                    ->toggleable()
                    ->label('资产编号'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([

            ])
            ->actions([
                // 前往设备详情
                DeviceCategoryAction::toDevice(),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
