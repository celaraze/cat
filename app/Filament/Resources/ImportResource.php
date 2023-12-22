<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ImportResource\Pages\FailedImportRow;
use App\Filament\Resources\ImportResource\Pages\Index;
use App\Filament\Resources\ImportResource\Pages\View;
use App\Models\Import;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class ImportResource extends Resource
{
    protected static ?string $model = Import::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'file_name';

    public static function getNavigationGroup(): ?string
    {
        return __('cat.log');
    }

    public static function getModelLabel(): string
    {
        return __('cat.import_log');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            FailedImportRow::class,
        ];

        return $page->generateNavigationItems($navigation_items);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('completed_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.completed_at')),
                Tables\Columns\TextColumn::make('file_name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.file_name')),
                Tables\Columns\TextColumn::make('processed_rows')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.processed_rows')),
                Tables\Columns\TextColumn::make('total_rows')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.total_rows')),
                Tables\Columns\TextColumn::make('successful_rows')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.successful_rows')),
                Tables\Columns\TextColumn::make('user_id')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.user_id')),
            ])
            ->filters([

            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->heading(__('cat.import_log'));
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Group::make()->schema([
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make()
                                ->schema([
                                    Group::make([
                                        TextEntry::make('completed_at')
                                            ->label(__('cat.completed_at'))
                                            ->badge()
                                            ->color('primary'),
                                        TextEntry::make('file_name')
                                            ->label(__('cat.file_name')),
                                        TextEntry::make('file_path')
                                            ->label(__('cat.file_path')),
                                        TextEntry::make('importer')
                                            ->label(__('cat.importer')),
                                    ]),
                                    Group::make([
                                        TextEntry::make('user_id')
                                            ->label(__('cat.user_id')),
                                        TextEntry::make('total_rows')
                                            ->label(__('cat.total_rows')),
                                        TextEntry::make('processed_rows')
                                            ->label(__('cat.processed_rows')),
                                        TextEntry::make('successful_rows')
                                            ->label(__('cat.successful_rows')),
                                    ]),
                                ]),
                        ]),
                    ]),
            ])->columnSpan(['lg' => 3]),
        ])->columns(3);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'failed_import_rows' => FailedImportRow::route('/{record}/failed_import_rows'),
        ];
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
