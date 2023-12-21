<?php

namespace App\Filament\Resources;

use App\Filament\Actions\AssetNumberRuleAction;
use App\Filament\Forms\AssetNumberRuleForm;
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

    protected static ?string $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationGroup(): ?string
    {
        return __('cat.basic_data');
    }

    public static function getModelLabel(): string
    {
        return __('cat.asset_number_rule');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
        ];
        $can_update_device = auth()->user()->can('update_asset::number::rule');
        if (!$can_update_device) {
            unset($navigation_items[2]);
        }

        return $page->generateNavigationItems($navigation_items);
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
        return $form->schema(AssetNumberRuleForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('cat.name')),
                Tables\Columns\TextColumn::make('formula')
                    ->label(__('cat.formula'))
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('auto_increment_length')
                    ->label(__('cat.auto_increment_length')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 删除
                Tables\Actions\DeleteAction::make()
                    ->closeModalByClickingAway(false)
                    ->visible(function () {
                        return auth()->user()->can('delete_asset::number::rule');
                    }),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 创建
                AssetNumberRuleAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_asset::number::rule');
                    }),
            ])
            ->heading(__('cat.asset_number_rule'));
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
