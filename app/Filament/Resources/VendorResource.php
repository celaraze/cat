<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VendorResource extends Resource
{
    protected static ?string $model = Vendor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = '厂商';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = '基础数据';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('名称')
                    ->required(),
                TextInput::make('address')
                    ->label('地址')
                    ->required(),
                TextInput::make('public_phone_number')
                    ->label('对公电话'),
                TextInput::make('referrer')
                    ->label('引荐人')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名称'),
                Tables\Columns\TextColumn::make('address')
                    ->label('地址'),
                Tables\Columns\TextColumn::make('public_phone_number')
                    ->label('对公电话'),
            ])
            ->filters([

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                    Tables\Actions\ForceDeleteBulkAction::make(),
//                    Tables\Actions\RestoreBulkAction::make(),
//                ]),
            ])
            ->emptyStateActions([

            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\HasContactRelationManager::class,
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
}
