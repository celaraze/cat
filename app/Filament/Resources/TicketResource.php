<?php

namespace App\Filament\Resources;

use App\Enums\Priority;
use App\Filament\Actions\TicketAction;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\TrackRelationManager;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $modelLabel = '工单';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'asset_number';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asset_number')
                    ->label('资产编号'),
                TextColumn::make('subject')
                    ->label('主题'),
                TextColumn::make('category.name')
                    ->label('分类'),
                TextColumn::make('priority')
                    ->label('优先级')
                    ->formatStateUsing(function (string $state) {
                        return Priority::array()[$state];
                    })
                    ->badge()
                    ->color(function (string $state) {
                        return Priority::colors()[$state];
                    }),
                TextColumn::make('user.name')
                    ->label('提交人'),
                TextColumn::make('assignee.name')
                    ->label('处理人'),
                TextColumn::make('created_at')
                    ->label('提交时间'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                // 抢单
                TicketAction::setAssignee()
                    ->visible(function (Ticket $ticket) {
                        return ! $ticket->service()->isSetAssignee();
                    }),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 创建
                TicketAction::createTicket(),
                ActionGroup::make([
                    // 前往分类
                    TicketAction::toTicketCategory(),
                ]),
            ]);
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
                                        TextEntry::make('asset_number')
                                            ->label('资产编号')
                                            ->badge()
                                            ->color('primary'),
                                        TextEntry::make('category.name')
                                            ->label('分类'),
                                        TextEntry::make('user.name')
                                            ->label('提交人'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('subject')
                                            ->label('主题'),
                                        TextEntry::make('priority')
                                            ->label('优先级')
                                            ->formatStateUsing(function (string $state) {
                                                return Priority::array()[$state];
                                            })
                                            ->badge()
                                            ->color(function (string $state) {
                                                return Priority::colors()[$state];
                                            }),
                                        TextEntry::make('assignee.name')
                                            ->label('处理人'),
                                    ]),
                                ]),
                        ]),
                    ]),
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('description')
                            ->html()
                            ->label('描述'),
                    ]),
            ])->columnSpan(['lg' => 2]),
        ])->columns(4);
    }

    public static function getRelations(): array
    {
        return [
            TrackRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Index::route('/'),
            'create' => Pages\Create::route('/create'),
            'view' => Pages\View::route('/{record}'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
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
