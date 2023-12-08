<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Actions\DeviceAction;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Ticket extends ManageRelatedRecords
{
    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'tickets';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $title = '工单';

    public static function getNavigationLabel(): string
    {
        return '工单';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('subject')
                    ->searchable()
                    ->toggleable()
                    ->label('主题'),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label('创建时间'),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label('提交人'),
                Tables\Columns\TextColumn::make('assignee.name')
                    ->searchable()
                    ->toggleable()
                    ->label('处理人'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(function (string $state) {
                        return $state ? '已处理' : '处理中';
                    })
                    ->badge()
                    ->color(function (string $state) {
                        return $state ? 'success' : 'warning';
                    })
                    ->label('状态'),
            ])
            ->filters([

            ])
            ->headerActions([
                // 创建
                DeviceAction::createTicket($this->getOwnerRecord()->getAttribute('asset_number'))
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
