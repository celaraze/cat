<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Actions\DeviceAction;
use App\Filament\Resources\DeviceResource;
use App\Models\DeviceHasSoftware;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSoftware extends ManageRelatedRecords
{
    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'hasSoftware';

    protected static ?string $navigationIcon = 'heroicon-m-squares-plus';

    protected static ?string $title = '软件';

    public static function getNavigationLabel(): string
    {
        return '软件';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('software.category.name')
                    ->searchable()
                    ->toggleable()
                    ->label('分类'),
                Tables\Columns\TextColumn::make('software.asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color(function (DeviceHasSoftware $device_has_software) {
                        if ($device_has_software->getAttribute('status') == '附加') {
                            return 'success';
                        } else {
                            return 'danger';
                        }
                    })
                    ->label('状态'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->toggleable()
                    ->label('操作时间'),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label('操作人'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                // 创建
                DeviceAction::createHasSoftware($this->getOwnerRecord())
                    ->visible(function () {
                        return auth()->user()->can('create_has_software_device');
                    }),
            ])
            ->actions([
                // 删除
                DeviceAction::deleteHasSoftware()
                    ->visible(function (DeviceHasSoftware $device_has_software) {
                        $can = auth()->user()->can('delete_has_software_device');

                        return $can && ! $device_has_software->service()->isDeleted();
                    }),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
