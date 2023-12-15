<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceAction;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use App\Models\DeviceHasSecret;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSecret extends ManageRelatedRecords
{
    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'hasSecrets';

    protected static ?string $navigationIcon = 'heroicon-m-key';

    protected static ?string $breadcrumb = '密钥';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '密钥';
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
                        ->label('名称'),
                    Tables\Columns\TextColumn::make('secret.username')
                        ->searchable()
                        ->toggleable()
                        ->label('账户'),
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
                    Tables\Columns\TextColumn::make('creator.name')
                        ->searchable()
                        ->toggleable()
                        ->label('操作人'),
                ]
            )
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->headerActions([
                // 创建
                DeviceAction::createHasSecret($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('create_has_secret_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired;
                    }),
            ])
            ->actions([
                // 删除
                DeviceAction::deleteHasSecret()
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
                DeviceAction::batchDeleteHasSecret()
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
