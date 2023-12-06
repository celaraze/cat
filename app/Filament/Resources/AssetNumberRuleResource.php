<?php

namespace App\Filament\Resources;

use App\Filament\Actions\AssetNumberRuleAction;
use App\Filament\Forms\SettingForm;
use App\Filament\Resources\AssetNumberRuleResource\Pages\Create;
use App\Filament\Resources\AssetNumberRuleResource\Pages\Edit;
use App\Filament\Resources\AssetNumberRuleResource\Pages\Index;
use App\Filament\Resources\AssetNumberRuleResource\Pages\View;
use App\Models\AssetNumberRule;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssetNumberRuleResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = AssetNumberRule::class;

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = '基础数据';

    protected static ?string $modelLabel = '资产编号规则';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Index::class,
            View::class,
            Edit::class,
        ]);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form->schema(SettingForm::createOrEdit());
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
                // 查看
                Tables\Actions\ViewAction::make(),
                // 编辑
                Tables\Actions\EditAction::make()
                    ->visible(function () {
                        return auth()->user()->can('update_asset::number::rule');
                    }),
                // 删除
                Tables\Actions\DeleteAction::make()
                    ->visible(function () {
                        return auth()->user()->can('delete_asset::number::rule');
                    }),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 创建
                AssetNumberRuleAction::createAssetNumberRule()
                    ->visible(function () {
                        return auth()->user()->can('create_asset::number::rule');
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'edit' => Edit::route('/{record}/edit'),
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
