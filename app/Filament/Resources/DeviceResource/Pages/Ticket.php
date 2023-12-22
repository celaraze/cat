<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Enums\TicketEnum;
use App\Filament\Actions\DeviceAction;
use App\Filament\Actions\TicketAction;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Ticket extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'tickets';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.ticket');
    }

    public static function getNavigationBadge(): ?string
    {
        return self::queryRecord()->tickets()->count();
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.ticket');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket.subject')),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket.created_at')),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket.user')),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/ticket.assignee')),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(function ($state) {
                        return TicketEnum::statusText($state);
                    })
                    ->badge()
                    ->color(function ($state) {
                        return TicketEnum::statusColor($state);
                    })
                    ->label(__('cat/ticket.status')),
            ])
            ->filters([

            ])
            ->headerActions([
                // 创建
                TicketAction::createFromDevice($this->getOwnerRecord()->getAttribute('asset_number'))
                    ->visible(function () {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();

                        return ! $device->service()->isRetired();
                    }),
            ])
            ->actions([
                // 前往工单
                DeviceAction::toTicket(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DissociateBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
