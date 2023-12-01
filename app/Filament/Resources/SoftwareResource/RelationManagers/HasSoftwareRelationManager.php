<?php

namespace App\Filament\Resources\SoftwareResource\RelationManagers;

use App\Filament\Actions\SoftwareAction;
use App\Models\DeviceHasSoftware;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSoftwareRelationManager extends RelationManager
{
    protected static string $relationship = 'hasSoftware';

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
                    ->color(function (DeviceHasSoftware $device_has_software) {
                        if ($device_has_software->getAttribute('status') == '附加') {
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
                SoftwareAction::createDeviceHasSoftware($this->getOwnerRecord()),
            ])
            ->actions([
                SoftwareAction::deleteDeviceHasSoftware()
                    ->visible(function (DeviceHasSoftware $device_has_software) {
                        return ! $device_has_software->service()->isDeleted();
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
