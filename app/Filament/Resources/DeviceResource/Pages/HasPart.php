<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceHasPartAction;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use App\Models\DeviceHasPart;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasPart extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'hasParts';

    protected static ?string $navigationIcon = 'heroicon-m-cpu-chip';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.part');
    }

    public static function getNavigationBadge(): ?string
    {
        return self::queryRecord()->parts()->count();
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.part');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(
                [
                    Tables\Columns\TextColumn::make('part.category.name')
                        ->searchable()
                        ->toggleable()
                        ->label(__('cat/part.category')),
                    Tables\Columns\TextColumn::make('part.asset_number')
                        ->searchable()
                        ->toggleable()
                        ->label(__('cat/part.asset_number')),
                    Tables\Columns\TextColumn::make('status')
                        ->toggleable()
                        ->badge()
                        ->formatStateUsing(function ($state) {
                            return AssetEnum::relationOperationText($state);
                        })
                        ->color(function ($state) {
                            return AssetEnum::relationOperationColor($state);
                        })
                        ->label(__('cat/part.status')),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->searchable()
                        ->toggleable()
                        ->label(__('cat/part.updated_at')),
                    Tables\Columns\TextColumn::make('creator.name')
                        ->searchable()
                        ->toggleable()
                        ->label(__('cat/part.creator')),
                ]
            )
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                // 创建
                DeviceHasPartAction::create($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('create_has_part_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired;
                    }),
            ])
            ->actions([
                // 删除
                DeviceHasPartAction::delete()
                    ->visible(function (DeviceHasPart $device_has_part) {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('delete_has_part_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired && ! $device_has_part->service()->isDeleted();
                    }),
            ])
            ->bulkActions([
                // 批量脱离配件
                DeviceHasPartAction::batchDelete()
                    ->visible(function () {
                        return auth()->user()->can('batch_delete_has_part_device');
                    }),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
