<?php

namespace App\Filament\Resources\PartResource\Pages;

use App\Filament\Actions\PartAction;
use App\Filament\Resources\PartResource;
use App\Models\DeviceHasPart;
use App\Models\Part;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasPart extends ManageRelatedRecords
{
    protected static string $resource = PartResource::class;

    protected static string $relationship = 'hasParts';

    protected static ?string $navigationIcon = 'heroicon-m-cpu-chip';

    protected static ?string $title = '附属记录';

    public static function getNavigationLabel(): string
    {
        return '附属记录';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('device.asset_number')
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('device.name')
                    ->label('名称'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function (DeviceHasPart $device_has_part) {
                        if ($device_has_part->getAttribute('status') == '附加') {
                            return 'success';
                        } else {
                            return 'danger';
                        }
                    })
                    ->label('状态'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('操作时间'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('操作人'),
            ])
            ->filters([

            ])
            ->headerActions([
                // 创建
                PartAction::createDeviceHasPart($this->getOwnerRecord())
                    ->visible(function () {
                        $can = auth()->user()->can('create_has_part_part');
                        $part = $this->getOwnerRecord();
                        /* @var Part $part */
                        return $can && !$part->hasParts()->count();
                    }),
            ])
            ->actions([
                // 删除
                PartAction::deleteDeviceHasPart()
                    ->visible(function (DeviceHasPart $device_has_part) {
                        $can = auth()->user()->can('delete_has_part_part');

                        return $can && !$device_has_part->service()->isDeleted();
                    }),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('id')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
