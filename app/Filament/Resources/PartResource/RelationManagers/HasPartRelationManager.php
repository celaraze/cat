<?php

namespace App\Filament\Resources\PartResource\RelationManagers;

use App\Filament\Actions\PartAction;
use App\Models\DeviceHasPart;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasPartRelationManager extends RelationManager
{
    protected static string $relationship = 'hasParts';

    protected static ?string $title = '设备';

    protected static ?string $icon = 'heroicon-o-cube';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('device.asset_number')
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
                        return auth()->user()->can('create_has_part_part');
                    }),
            ])
            ->actions([
                // 删除
                PartAction::deleteDeviceHasPart()
                    ->visible(function (DeviceHasPart $device_has_part) {
                        $can = auth()->user()->can('delete_has_part_part');

                        return $can && ! $device_has_part->service()->isDeleted();
                    }),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
