<?php

namespace App\Filament\Resources;

use App\Enums\TicketEnum;
use App\Filament\Actions\TicketAction;
use App\Filament\Resources\TicketResource\Pages\Index;
use App\Filament\Resources\TicketResource\Pages\Track;
use App\Filament\Resources\TicketResource\Pages\View;
use App\Models\Ticket;
use App\Services\TicketCategoryService;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
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

class TicketResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'asset_number';

    public static function getModelLabel(): string
    {
        return __('cat/menu.ticket');
    }

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.ticket');
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'set_assignee',
            'create_track',
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
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
                    ->label(__('cat/ticket.asset_number')),
                TextColumn::make('subject')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket.subject')),
                TextColumn::make('category.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket.category')),
                TextColumn::make('priority')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket.priority'))
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
                    ->label(__('cat/ticket.user')),
                TextColumn::make('assignee.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket.assignee')),
                TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket.created_at')),
                TextColumn::make('status')
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(function (string $state) {
                        return TicketEnum::statusText($state);
                    })
                    ->color(function (string $state) {
                        return TicketEnum::statusColor($state);
                    })
                    ->label(__('cat/ticket.status')),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->multiple()
                    ->options(TicketCategoryService::pluckOptions())
                    ->label(__('cat/ticket.category_id')),
                SelectFilter::make('priority')
                    ->multiple()
                    ->options(TicketEnum::allPriorityText())
                    ->label(__('cat/ticket.priority')),
                SelectFilter::make('status')
                    ->multiple()
                    ->options(TicketEnum::allStatusText())
                    ->label(__('cat/ticket.status')),
            ])
            ->actions([
                // 抢单
                TicketAction::setAssignee()
                    ->visible(function (Ticket $ticket) {
                        $can = auth()->user()->can('set_assignee_ticket');

                        return $can && ! $ticket->service()->isSetAssignee();
                    }),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 创建
                TicketAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_ticket');
                    }),
                ActionGroup::make([
                    // 前往分类
                    TicketAction::toCategory(),
                ])
                    ->label(__('cat/ticket.action.advance'))
                    ->icon('heroicon-m-cog-8-tooth')
                    ->button(),
            ])
            ->heading(__('cat/menu.ticket'));
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
                            ->label(__('cat/ticket.asset_number')),
                    ]),
                Section::make()
                    ->schema([
                        TextEntry::make('description')
                            ->html()
                            ->label(__('cat/ticket.description')),
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
                                            ->label(__('cat/ticket.category')),
                                        TextEntry::make('user.name')
                                            ->label(__('cat/ticket.user')),
                                        TextEntry::make('assignee.name')
                                            ->label(__('cat/ticket.assignee')),
                                    ]),
                                    Group::make([
                                        TextEntry::make('subject')
                                            ->label(__('cat/ticket.subject')),
                                        TextEntry::make('priority')
                                            ->label(__('cat/ticket.priority'))
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
