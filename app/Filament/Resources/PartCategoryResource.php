<?php

namespace App\Filament\Resources;

use App\Filament\Actions\PartCategoryAction;
use App\Filament\Forms\PartCategoryForm;
use App\Filament\Imports\PartCategoryImporter;
use App\Filament\Resources\PartCategoryResource\Pages\Edit;
use App\Filament\Resources\PartCategoryResource\Pages\Index;
use App\Filament\Resources\PartCategoryResource\Pages\Part;
use App\Filament\Resources\PartCategoryResource\Pages\View;
use App\Models\PartCategory;
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

class PartCategoryResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = PartCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('cat/part_category');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
            Part::class,
        ];
        $can_update_part_category = auth()->user()->can('update_part::category');
        if (! $can_update_part_category) {
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
        return $form->schema(PartCategoryForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/resource.part_category.name')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 删除
                PartCategoryAction::delete()
                    ->visible(function () {
                        return auth()->user()->can('delete_part::category');
                    }),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(PartCategoryImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label(__('cat/action.import'))
                    ->visible(function () {
                        return auth()->user()->can('import_part::category');
                    }),
                // 导出
                ExportAction::make()
                    ->label(__('cat/action.export'))
                    ->visible(function () {
                        return auth()->user()->can('export_part::category');
                    }),
                // 创建
                PartCategoryAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_part::category');
                    }),
                // 返回配件
                PartCategoryAction::toPart(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'edit' => Edit::route('/{record}/edit'),
            'parts' => Part::route('/{record}/parts'),
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
