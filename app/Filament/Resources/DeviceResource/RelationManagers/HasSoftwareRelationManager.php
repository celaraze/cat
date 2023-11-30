<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use App\Filament\Actions\DeviceAction;
use App\Models\DeviceHasSoftware;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSoftwareRelationManager extends RelationManager
{
    protected static string $relationship = 'hasSoftware';

    protected static ?string $title = '软件';

    protected static ?string $icon = 'heroicon-m-squares-plus';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('asset_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('software.asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('software.category.name')
                    ->label('分类'),
                Tables\Columns\TextColumn::make('software.asset_number')
                    ->label('资产编号'),
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
                    ->label('操作人')
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                DeviceAction::createDeviceHasSoftware($this->getOwnerRecord())
            ])
            ->actions([
                DeviceAction::deleteDeviceHasSoftware()
                    ->visible(function (DeviceHasSoftware $device_has_software) {
                        return !$device_has_software->service()->isDeleted();
                    })
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn(Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
