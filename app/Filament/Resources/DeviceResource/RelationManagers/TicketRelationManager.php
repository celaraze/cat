<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use App\Filament\Actions\DeviceAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TicketRelationManager extends RelationManager
{
    protected static string $relationship = 'tickets';

    protected static ?string $title = '工单';

    protected static ?string $icon = 'heroicon-o-document-text';

    public static function getBadge(Model $ownerRecord, string $pageClass): ?string
    {
        return $ownerRecord->tickets()->count();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('subject'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->label('主题'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('创建时间'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('提交人'),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->label('处理人'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->formatStateUsing(function (string $state) {
                        return $state ? '已处理' : '处理中';
                    })
                    ->badge()
                    ->color(function (string $state) {
                        return $state ? 'success' : 'warning';
                    })
                    ->label('状态'),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([

            ])
            ->headerActions([
                DeviceAction::createTicket($this->getOwnerRecord()->getAttribute('asset_number')),
            ])
            ->actions([
                DeviceAction::toTicket(),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
