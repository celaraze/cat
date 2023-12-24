<?php

namespace App\Filament\Resources\PartCategoryResource\Pages;

use App\Filament\Actions\PartCategoryAction;
use App\Filament\Resources\PartCategoryResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Part extends ManageRelatedRecords
{
    protected static string $resource = PartCategoryResource::class;

    protected static string $relationship = 'parts';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.part');
    }

    public function getTitle(): string|Htmlable
    {
        return __('cat/menu.part');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/part.asset_number')),
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
