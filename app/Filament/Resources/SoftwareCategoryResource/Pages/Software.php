<?php

namespace App\Filament\Resources\SoftwareCategoryResource\Pages;

use App\Filament\Actions\SoftwareCategoryAction;
use App\Filament\Resources\SoftwareCategoryResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Software extends ManageRelatedRecords
{
    protected static string $resource = SoftwareCategoryResource::class;

    protected static string $relationship = 'software';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.software');
    }

    public function getTitle(): string|Htmlable
    {
        return __('cat/menu.software');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/software.asset_number')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([

            ])
            ->actions([
                // 前往软件清单
                SoftwareCategoryAction::toSoftwareView(),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]));
    }
}
