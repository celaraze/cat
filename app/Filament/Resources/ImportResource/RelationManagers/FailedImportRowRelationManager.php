<?php

namespace App\Filament\Resources\ImportResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class FailedImportRowRelationManager extends RelationManager
{
    protected static string $relationship = 'failedImportRows';

    protected static ?string $title = '失败行';

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
                    ->label('创建时间'),
                Tables\Columns\TextColumn::make('data')
                    ->label('数据'),
                Tables\Columns\TextColumn::make('validation_error')
                    ->label('错误类型'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
