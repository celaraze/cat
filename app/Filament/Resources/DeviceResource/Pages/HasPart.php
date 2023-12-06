<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Filament\Actions\DeviceAction;
use App\Filament\Resources\DeviceResource;
use App\Models\DeviceHasPart;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasPart extends ManageRelatedRecords
{
    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'hasParts';

    protected static ?string $navigationIcon = 'heroicon-m-cpu-chip';

    protected static ?string $title = '配件';

    public static function getNavigationLabel(): string
    {
        return '配件';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(
                [
                    Tables\Columns\TextColumn::make('part.category.name')
                        ->label('分类'),
                    Tables\Columns\TextColumn::make('part.asset_number')
                        ->label('资产编号'),
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
                ]
            )
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                // 创建
                DeviceAction::createDeviceHasPart($this->getOwnerRecord())
                    ->visible(function () {
                        return auth()->user()->can('create_has_part_device');
                    }),
            ])
            ->actions([
                // 删除
                DeviceAction::deleteDeviceHasPart()
                    ->visible(function (DeviceHasPart $device_has_part) {
                        $can = auth()->user()->can('delete_has_part_device');

                        return $can && ! $device_has_part->service()->isDeleted();
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
