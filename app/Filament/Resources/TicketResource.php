<?php

namespace App\Filament\Resources;

use App\Enums\TicketEnum;
use App\Filament\Actions\TicketAction;
use App\Filament\Resources\TicketResource\Pages\Edit;
use App\Filament\Resources\TicketResource\Pages\Index;
use App\Filament\Resources\TicketResource\Pages\Track;
use App\Filament\Resources\TicketResource\Pages\View;
use App\Models\Ticket;
use App\Services\TicketCategoryService;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
            Track::class,
        ];

        return $page->generateNavigationItems($navigation_items);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label('资产编号'),
                TextColumn::make('subject')
                    ->searchable()
                    ->toggleable()
                    ->label('主题'),
                TextColumn::make('category.name')
                    ->searchable()
                    ->toggleable()
                    ->label('分类'),
                TextColumn::make('priority')
                    ->searchable()
                    ->toggleable()
                    ->label('优先级')
                    ->formatStateUsing(function (string $state) {
                        return TicketEnum::priorityText($state);
                    })
                    ->badge()
                    ->color(function (string $state) {
                        return TicketEnum::priorityColor($state);
                    }),
                TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label('提交人'),
                TextColumn::make('assignee.name')
                    ->searchable()
                    ->toggleable()
                    ->label('处理人'),
                TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label('提交时间'),
                TextColumn::make('status')
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(function (string $state) {
                        return TicketEnum::statusText($state);
                    })
                    ->color(function (string $state) {
                        return TicketEnum::statusColor($state);
                    })
                    ->label('状态'),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->multiple()
                    ->options(TicketCategoryService::pluckOptions())
                    ->label('分类'),
                SelectFilter::make('priority')
                    ->multiple()
                    ->options(TicketEnum::allPriorityText())
                    ->label('优先级'),
                SelectFilter::make('status')
                    ->multiple()
                    ->options(TicketEnum::allStatusText())
                    ->label('状态'),
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
                TicketAction::create(),
                ActionGroup::make([
                    // 前往分类
                    TicketAction::toCategory(),
                ])
                    ->label('高级')
                    ->icon('heroicon-m-cog-8-tooth')
                    ->button(),
            ])
            ->heading('工单');
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Group::make()->schema([
                Section::make()
                    ->schema([
                        TextEntry::make('asset_number')
                            ->badge()
                            ->color('primary')
                            ->hintActions([
                                TicketAction::finish()
                                    ->visible(function (Ticket $ticket) {
                                        $is_finished = $ticket->getAttribute('status') == 2;

                                        return ! $is_finished && $ticket->getAttribute('user_id') == auth()->id();
                                    }),
                            ])
                            ->label('资产编号'),
                    ]),
                Section::make()
                    ->schema([
                        TextEntry::make('description')
                            ->html()
                            ->label('描述'),
                    ]),
            ])->columnSpan(['lg' => 1]),
            Group::make()->schema([
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make()
                                ->schema([
                                    Group::make([
                                        TextEntry::make('category.name')
                                            ->label('分类'),
                                        TextEntry::make('user.name')
                                            ->label('提交人'),
                                        TextEntry::make('assignee.name')
                                            ->label('处理人'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('subject')
                                            ->label('主题'),
                                        TextEntry::make('priority')
                                            ->label('优先级')
                                            ->formatStateUsing(function (string $state) {
                                                return TicketEnum::priorityText($state);
                                            })
                                            ->badge()
                                            ->color(function (string $state) {
                                                return TicketEnum::priorityColor($state);
                                            }),
                                    ]),
                                ]),
                        ]),
                    ]),
            ]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'tracks' => Track::route('/{record}/tracks'),
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
