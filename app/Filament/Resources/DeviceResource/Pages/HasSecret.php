<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceAction;
use App\Filament\Actions\DeviceHasSecretAction;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use App\Models\DeviceHasSecret;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSecret extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'hasSecrets';

    protected static ?string $navigationIcon = 'heroicon-m-key';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.secret');
    }

    public static function getNavigationBadge(): ?string
    {
        return self::queryRecord()->secrets()->count();
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.secret');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(
                [
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
                        ->label(__('cat/secret.status')),
                    Tables\Columns\TextColumn::make('updated_at')
                        ->searchable()
                        ->toggleable()
                        ->label(__('cat/secret.updated_at')),
                    Tables\Columns\TextColumn::make('creator.name')
                        ->searchable()
                        ->toggleable()
                        ->label(__('cat/secret.creator')),
                ]
            )
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                // 创建
                DeviceHasSecretAction::create($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('create_has_secret_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired;
                    }),
            ])
            ->actions([
                // 查看密码
                DeviceAction::viewToken()
                    ->visible(function () {
                        return auth()->user()->can('view_token_device');
                    }),
                // 删除
                DeviceHasSecretAction::delete()
                    ->visible(function (DeviceHasSecret $device_has_secret) {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('delete_has_secret_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired && ! $device_has_secret->service()->isDeleted();
                    }),
            ])
            ->bulkActions([
                // 批量脱离配件
                DeviceHasSecretAction::batchDelete()
                    ->visible(function () {
                        return auth()->user()->can('batch_delete_has_secret_device');
                    }),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
