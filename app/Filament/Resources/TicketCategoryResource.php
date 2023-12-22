<?php

namespace App\Filament\Resources;

use App\Filament\Actions\TicketCategoryAction;
use App\Filament\Forms\TicketCategoryForm;
use App\Filament\Imports\TicketCategoryImporter;
use App\Filament\Resources\TicketCategoryResource\Pages\Edit;
use App\Filament\Resources\TicketCategoryResource\Pages\Index;
use App\Filament\Resources\TicketCategoryResource\Pages\View;
use App\Models\TicketCategory;
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

class TicketCategoryResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = TicketCategory::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getModelLabel(): string
    {
        return __('cat.menu.ticket_category');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
        ];
        $can_update_ticket_category = auth()->user()->can('update_ticket::category');
        if (! $can_update_ticket_category) {
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
                    ->searchable()
                    ->label(__('cat.ticket_category.name')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 删除
                TicketCategoryAction::delete(),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 导入
                ImportAction::make()
                    ->importer(TicketCategoryImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->label(__('cat.action.import'))
                    ->visible(function () {
                        return auth()->user()->can('import_ticket::category');
                    }),
                // 导出
                ExportAction::make()
                    ->label(__('cat.action.export'))
                    ->visible(function () {
                        return auth()->user()->can('export_ticket::category');
                    }),
                // 创建
                TicketCategoryAction::create(),
                // 前往工单
                TicketCategoryAction::backToTicket(),
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
