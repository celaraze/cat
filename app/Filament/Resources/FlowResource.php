<?php

namespace App\Filament\Resources;

use App\Filament\Actions\FlowAction;
use App\Filament\Forms\FlowForm;
use App\Filament\Resources\FlowResource\Pages;
use App\Filament\Resources\FlowResource\RelationManagers\HasNodeRelationManager;
use App\Models\Flow;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FlowResource extends Resource
{
    protected static ?string $model = Flow::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = '流程';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = '工作流';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(FlowForm::createFlow());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名称'),
                Tables\Columns\TextColumn::make('tag')
                    ->label('唯一标识')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                FlowAction::createFlow()
            ]);
    }

    public static function getRelations(): array
    {
        return [
            HasNodeRelationManager::make()
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Index::route('/'),
            'create' => Pages\Create::route('/create'),
            'edit' => Pages\Edit::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
