<?php

namespace App\Filament\Resources;

use App\Filament\Actions\TicketAction;
use App\Filament\Forms\TicketCategoryForm;
use App\Filament\Resources\TicketCategoryResource\Pages;
use App\Models\TicketCategory;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketCategoryResource extends Resource
{
    protected static ?string $model = TicketCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = '工单分类';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(TicketCategoryForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->label('名称'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 前往工单
                TicketAction::toTicket(),
                // 创建
                TicketAction::createTicketCategory(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Index::route('/'),
            'edit' => Pages\Edit::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
