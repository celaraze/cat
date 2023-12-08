<?php

namespace App\Filament\Resources;

use App\Filament\Actions\TicketAction;
use App\Filament\Actions\TicketCategoryAction;
use App\Filament\Forms\TicketCategoryForm;
use App\Filament\Resources\TicketCategoryResource\Pages\Edit;
use App\Filament\Resources\TicketCategoryResource\Pages\Index;
use App\Filament\Resources\TicketCategoryResource\Pages\View;
use App\Models\TicketCategory;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
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

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Index::class,
            View::class,
            Edit::class,
        ]);
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
                    ->label('名称'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 详情
                Tables\Actions\ViewAction::make(),
                // 编辑
                Tables\Actions\EditAction::make(),
                // 删除
                TicketCategoryAction::delete(),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 创建
                TicketCategoryAction::create(),
                // 前往工单
                TicketAction::toTickets(),
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
