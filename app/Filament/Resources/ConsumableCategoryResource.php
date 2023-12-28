<?php

namespace App\Filament\Resources;

use App\Filament\Actions\ConsumableCategoryAction;
use App\Filament\Forms\ConsumableCategoryForm;
use App\Filament\Imports\ConsumableCategoryImporter;
use App\Filament\Resources\ConsumableCategoryResource\Pages;
use App\Filament\Resources\ConsumableCategoryResource\Pages\Edit;
use App\Filament\Resources\ConsumableCategoryResource\Pages\Index;
use App\Filament\Resources\ConsumableCategoryResource\Pages\View;
use App\Models\ConsumableCategory;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class ConsumableCategoryResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = ConsumableCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('cat/menu.consumable_category');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
        ];
        $consumable_category_service = $page->getWidgetData()['record']->service();
        $can_update_consumable_category = auth()->user()->can('update_consumable::category');
        if ($consumable_category_service->isDeleted() || ! $can_update_consumable_category) {
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
            'import',
            'export',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ConsumableCategoryForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/consumable_category.name')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 删除
                ConsumableCategoryAction::delete()
                    ->visible(function (ConsumableCategory $consumable_category) {
                        $is_deleted = $consumable_category->service()->isDeleted();
                        $can = auth()->user()->can('delete_consumable::category');

                        return ! $is_deleted && $can;
                    }),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(ConsumableCategoryImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label(__('cat/action.import'))
                    ->visible(function () {
                        return auth()->user()->can('import_consumable::category');
                    }),
                // 导出
                ExportAction::make()
                    ->label(__('cat/action.export'))
                    ->visible(function () {
                        return auth()->user()->can('export_consumable::category');
                    }),
                // 创建
                ConsumableCategoryAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_consumable::category');
                    }),
                ConsumableCategoryAction::toConsumable(),
            ])
            ->heading(__('cat/menu.consumable_category'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Index::route('/'),
            'view' => Pages\View::route('/{record}'),
            'edit' => Pages\Edit::route('/{record}/edit'),
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
