<?php

namespace App\Filament\Resources;

use App\Filament\Actions\SoftwareCategoryAction;
use App\Filament\Forms\SoftwareCategoryForm;
use App\Filament\Imports\SoftwareCategoryImporter;
use App\Filament\Resources\SoftwareCategoryResource\Pages\Edit;
use App\Filament\Resources\SoftwareCategoryResource\Pages\Index;
use App\Filament\Resources\SoftwareCategoryResource\Pages\Software;
use App\Filament\Resources\SoftwareCategoryResource\Pages\View;
use App\Models\SoftwareCategory;
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

class SoftwareCategoryResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = SoftwareCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('cat/menu.software_category');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
            Software::class,
        ];
        $can_update_software_category = auth()->user()->can('update_software::category');
        if (! $can_update_software_category) {
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
        return $form->schema(SoftwareCategoryForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/software_category.name')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 删除
                SoftwareCategoryAction::delete()
                    ->visible(function () {
                        return auth()->user()->can('delete_software::category');
                    }),
            ])
            ->bulkActions([
            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(SoftwareCategoryImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label(__('cat/action.import'))
                    ->visible(function () {
                        return auth()->user()->can('import_software::category');
                    }),
                // 导出
                ExportAction::make()
                    ->label(__('cat/action.export'))
                    ->visible(function () {
                        return auth()->user()->can('export_software::category');
                    }),
                // 创建
                SoftwareCategoryAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_software::category');
                    }),
                // 前往软件
                SoftwareCategoryAction::toSoftware(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'edit' => Edit::route('/{record}/edit'),
            'software' => Software::route('/{record}/software'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
}
