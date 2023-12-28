<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Actions\TicketHasTrackAction;
use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Track extends ManageRelatedRecords
{
    protected static string $resource = TicketResource::class;

    protected static string $relationship = 'tracks';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.ticket_has_track');
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.ticket_has_track');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket_has_track.created_at')),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket_has_track.user.name')),
                Tables\Columns\TextColumn::make('comment')
                    ->searchable()
                    ->toggleable()
                    // todo wrap 不起作用？
                    ->wrap()
                    ->html()
                    ->label(__('cat/ticket_has_track.comment')),
                Tables\Columns\TextColumn::make('minutes')
                    ->searchable()
                    ->toggleable()
                    ->alignRight()
                    ->badge()
                    ->label(__('cat/ticket_has_track.minutes')),
            ])
            ->filters([

            ])
            ->headerActions([
                // 创建
                TicketHasTrackAction::create($this->getOwnerRecord())
                    ->visible(function () {
                        $is_completed = $this->getOwnerRecord()->service()->isCompleted();
                        $can = auth()->user()->can('create_track_ticket');

                        return ! $is_completed && $can;
                    }),
            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
