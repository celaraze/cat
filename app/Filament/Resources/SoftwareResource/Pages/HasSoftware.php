<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\SoftwareAction;
use App\Filament\Resources\SoftwareResource;
use App\Models\DeviceHasSoftware;
use App\Models\Software;
use Filament\Forms\Form;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSoftware extends ManageRelatedRecords
{
    protected static string $resource = SoftwareResource::class;

    protected static string $relationship = 'hasSoftware';

    protected static ?string $icon = 'heroicon-o-cube';

    protected static ?string $title = '附属记录';

    public static function getNavigationLabel(): string
    {
        return '附属记录';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('device.asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('device.name')
                    ->searchable()
                    ->toggleable()
                    ->label('名称'),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return AssetEnum::relationOperationText($state);
                    })
                    ->color(function ($state) {
                        return AssetEnum::relationOperationColor($state);
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

            ])
            ->headerActions([
                // 创建
                SoftwareAction::createDeviceHasSoftware($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Software $software */
                        $software = $this->getOwnerRecord();
                        $can = auth()->user()->can('create_has_software_software');
                        $is_retired = $software->service()->isRetired();

                        return $can && ! $is_retired;
                    }),
            ])
            ->actions([
                // 删除
                SoftwareAction::deleteDeviceHasSoftware()
                    ->visible(function (DeviceHasSoftware $device_has_software) {
                        $can = auth()->user()->can('delete_has_software_software');
                        $is_retired = $device_has_software->software()->first()?->service()->isRetired() ?? false;

                        return $can && ! $is_retired && ! $device_has_software->service()->isDeleted();
                    }),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('id')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
