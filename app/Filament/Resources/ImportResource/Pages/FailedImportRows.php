<?php

namespace App\Filament\Resources\ImportResource\Pages;

use App\Filament\Resources\ImportResource;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;

class FailedImportRows extends ManageRelatedRecords
{
    protected static string $resource = ImportResource::class;

    protected static string $relationship = 'failedImportRows';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $breadcrumb = '失败行记录';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '失败行记录';
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
                    ->label('创建时间'),
                Tables\Columns\TextColumn::make('data')
                    ->searchable()
                    ->toggleable()
                    ->label('数据'),
                Tables\Columns\TextColumn::make('validation_error')
                    ->searchable()
                    ->toggleable()
                    ->label('错误类型'),
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
