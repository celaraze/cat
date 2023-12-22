<?php

namespace App\Filament\Resources\PartResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceHasPartAction;
use App\Filament\Resources\PartResource;
use App\Models\DeviceHasPart;
use App\Models\Part;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasPart extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = PartResource::class;

    protected static string $relationship = 'hasParts';

    protected static ?string $navigationIcon = 'heroicon-s-server';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.device_has_part');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::queryRecord()->devices()->first()?->getAttribute('asset_number');
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.device_has_part');
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
                    ->label(__('cat/device.category')),
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
                    ->label(__('cat/device_has_part.status')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device_has_part.updated_at')),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device_has_part.user')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options(AssetEnum::allRelationOperationText())
                    ->label(__('cat/device_has_part.status')),
            ])
            ->headerActions([
                // 创建
                DeviceHasPartAction::createFromPart($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Part $part */
                        $part = $this->getOwnerRecord();
                        $can = auth()->user()->can('create_has_part_part');
                        $is_retired = $part->service()->isRetired();

                        return $can && ! $is_retired && ! $part->hasParts()->count();
                    }),
            ])
            ->actions([
                // 删除
                DeviceHasPartAction::deleteFromPart()
                    ->visible(function (DeviceHasPart $device_has_part) {
                        $can = auth()->user()->can('delete_has_part_part');
                        $is_retired = $device_has_part->part()->first()?->service()->isRetired() ?? false;

                        return $can && ! $is_retired && ! $device_has_part->service()->isDeleted();
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
