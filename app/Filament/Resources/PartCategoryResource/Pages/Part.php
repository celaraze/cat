<?php

namespace App\Filament\Resources\PartCategoryResource\Pages;

use App\Filament\Actions\PartCategoryAction;
use App\Filament\Resources\PartCategoryResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Part extends ManageRelatedRecords
{
    protected static string $resource = PartCategoryResource::class;

    protected static string $relationship = 'parts';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = '配件';

    public static function getNavigationLabel(): string
    {
        return '配件';
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
                // 前往配件详情
                PartCategoryAction::toPartView(),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
