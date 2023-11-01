<?php

namespace App\Filament\Resources\Information;

use App\Filament\Actions\Imformation\PartAction;
use App\Filament\Actions\ImportAction;
use App\Filament\Imports\PartCategoryImporter;
use App\Http\Middleware\FilamentLockTab;
use App\Models\Information\PartCategory;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PartCategoryResource extends Resource
{
    protected static ?string $model = PartCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static string|array $routeMiddleware = FilamentLockTab::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('名称')
                    ->maxLength(255)
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名称'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make(new PartCategoryImporter()),
                PartAction::createPartCategory(),
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
            'index' => PartCategoryResource\Pages\Index::route('/'),
            'create' => PartCategoryResource\Pages\Create::route('/create'),
            'edit' => PartCategoryResource\Pages\Edit::route('/{record}/edit'),
            'view' => PartCategoryResource\Pages\View::route('/{record}'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
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
