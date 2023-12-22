<?php

namespace App\Filament\Resources\SecretResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceHasSecretAction;
use App\Filament\Resources\SecretResource;
use App\Models\DeviceHasSecret;
use App\Models\Secret;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSecret extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = SecretResource::class;

    protected static string $relationship = 'hasSecrets';

    protected static ?string $navigationIcon = 'heroicon-s-server';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.device_has_secret');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::queryRecord()->devices()->first()?->getAttribute('asset_number');
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.device_has_secret');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('secret.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/secret.name')),
                Tables\Columns\TextColumn::make('secret.username')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/secret.username')),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return AssetEnum::relationOperationText($state);
                    })
                    ->color(function ($state) {
                        return AssetEnum::relationOperationColor($state);
                    })
                    ->label(__('cat/device_has_secret.status')),
                Tables\Columns\TextColumn::make('updated_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device_has_secret.updated_at')),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/user.name')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options(AssetEnum::allRelationOperationText())
                    ->label(__('cat/device_has_secret.status')),
            ])
            ->headerActions([
                // 创建
                DeviceHasSecretAction::create($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Secret $secret */
                        $secret = $this->getOwnerRecord();
                        $is_retired = $secret->service()->isRetired();
                        $can = auth()->user()->can('create_has_secret_secret');

                        return $can && ! $is_retired && ! $secret->hasSecrets()->count();
                    }),
            ])
            ->actions([
                // 删除
                DeviceHasSecretAction::delete()
                    ->visible(function (DeviceHasSecret $device_has_secret) {
                        /* @var Secret $secret */
                        $secret = $this->getOwnerRecord();
                        $is_retired = $secret->service()->isRetired();
                        $can = auth()->user()->can('delete_has_secret_secret');

                        return $can && ! $is_retired && ! $device_has_secret->service()->isDeleted();
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
