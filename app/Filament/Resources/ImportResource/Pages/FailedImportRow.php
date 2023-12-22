<?php

namespace App\Filament\Resources\ImportResource\Pages;

use App\Filament\Resources\ImportResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class FailedImportRow extends ManageRelatedRecords
{
    protected static string $resource = ImportResource::class;

    protected static string $relationship = 'failedImportRows';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.failed_import_row');
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.failed_import_row');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('import_id')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/failed_import_row.created_at')),
                Tables\Columns\TextColumn::make('data')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/failed_import_row.data')),
                Tables\Columns\TextColumn::make('validation_error')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/failed_import_row.validation_error')),
            ])
            ->filters([

            ])
            ->headerActions([

            ])
            ->actions([

            ])
            ->bulkActions([

            ]);
    }
}
