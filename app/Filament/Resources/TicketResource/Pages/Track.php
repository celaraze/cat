<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Actions\TicketAction;
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

    protected static ?string $title = '记录';

    public static function getNavigationLabel(): string
    {
        return '记录';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('用户'),
                Tables\Columns\TextColumn::make('comment')
                    ->label('评论')
                    // todo wrap 不起作用？
                    ->wrap()
                    ->html(),
            ])
            ->filters([

            ])
            ->headerActions([
                // 创建
                TicketAction::createTicketHasTrack($this->getOwnerRecord()),
            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
