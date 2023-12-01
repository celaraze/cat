<?php

namespace App\Filament\Resources;

use App\Filament\Actions\AssetNumberRuleAction;
use App\Filament\Forms\SettingForm;
use App\Filament\Resources\AssetNumberRuleResource\Pages;
use App\Models\AssetNumberRule;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetNumberRuleResource extends Resource
{
    protected static ?string $model = AssetNumberRule::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = '基础数据';

    protected static ?string $modelLabel = '资产编号规则';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(SettingForm::createOrEditSettingAssetNumber());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名称'),
                Tables\Columns\TextColumn::make('formula')
                    ->label('公式')
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('auto_increment_length')
                    ->label('自增长度'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->headerActions([
                AssetNumberRuleAction::createAssetNumberRule(),
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
