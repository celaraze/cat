<?php

namespace App\Filament\Resources\TicketResource\RelationManagers;

use App\Filament\Actions\TicketAction;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class TrackRelationManager extends RelationManager
{
    protected static string $relationship = 'tracks';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('ticket_id')
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
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->headerActions([
                TicketAction::createTicketHasTrack($this->getOwnerRecord()),
            ])
            ->actions([

            ])
            ->bulkActions([

            ]);
    }
}
