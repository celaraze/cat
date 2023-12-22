<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceHasSoftwareAction;
use App\Filament\Resources\SoftwareResource;
use App\Models\DeviceHasSoftware;
use App\Models\Software;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSoftware extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = SoftwareResource::class;

    protected static string $relationship = 'hasSoftware';

    protected static ?string $icon = 'heroicon-o-cube';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.device_has_software');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::queryRecord()->devices()->first()?->getAttribute('asset_number');
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.device_has_software');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('device.asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device.asset_number')),
                Tables\Columns\TextColumn::make('device.category.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device_category.name')),
                Tables\Columns\TextColumn::make('device.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device.name')),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return AssetEnum::relationOperationText($state);
                    })
                    ->color(function ($state) {
                        return AssetEnum::relationOperationColor($state);
                    })
                    ->label(__('cat/device_has_software.status')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device_has_software.updated_at')),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/user.name')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options(AssetEnum::allRelationOperationText())
                    ->label(__('cat/device_has_software.status')),
            ])
            ->headerActions([
                // 创建
                DeviceHasSoftwareAction::create($this->getOwnerRecord())
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
                DeviceHasSoftwareAction::delete()
                    ->visible(function (DeviceHasSoftware $device_has_software) {
                        $can = auth()->user()->can('delete_has_software_software');
                        $is_retired = $device_has_software->software()->first()?->service()->isRetired() ?? false;

                        return $can && ! $is_retired && ! $device_has_software->service()->isDeleted();
                    }),
            ])
            ->bulkActions([
                // 批量脱离软件
                DeviceHasSoftwareAction::batchDelete()
                    ->visible(function () {
                        return auth()->user()->can('delete_has_software_software');
                    }),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('id')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
