<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceAction;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use App\Models\DeviceHasSoftware;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSoftware extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'hasSoftware';

    protected static ?string $navigationIcon = 'heroicon-m-squares-plus';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat.menu.software');
    }

    public static function getNavigationBadge(): ?string
    {
        return self::queryRecord()->software()->count();
    }

    public function getBreadcrumb(): string
    {
        return __('cat.menu.software');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('software.category.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.software.category')),
                Tables\Columns\TextColumn::make('software.asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.software.asset_number')),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return AssetEnum::relationOperationText($state);
                    })
                    ->color(function ($state) {
                        return AssetEnum::relationOperationColor($state);
                    })
                    ->label(__('cat.software.status')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.software.updated_at')),
                Tables\Columns\TextColumn::make('creator.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.software.creator')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                // 创建
                DeviceAction::createHasSoftware($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('create_has_software_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired;
                    }),
            ])
            ->actions([
                // 删除
                DeviceAction::deleteHasSoftware()
                    ->visible(function (DeviceHasSoftware $device_has_software) {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('delete_has_software_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired && ! $device_has_software->service()->isDeleted();
                    }),
            ])
            ->bulkActions([
                // 批量脱离软件
                DeviceAction::batchDeleteHasSoftware()
                    ->visible(function () {
                        return auth()->user()->can('batch_delete_has_software_device');
                    }),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
