<?php

namespace App\Filament\Resources\SecretResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\SecretAction;
use App\Filament\Resources\SecretResource;
use App\Models\DeviceHasSecret;
use App\Models\Secret;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasSecret extends ManageRelatedRecords
{
    protected static string $resource = SecretResource::class;

    protected static string $relationship = 'hasSecrets';

    protected static ?string $navigationIcon = 'heroicon-s-server';

    protected static ?string $breadcrumb = '附属记录';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '附属记录';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
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
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->label('操作人'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->multiple()
                    ->options(AssetEnum::allRelationOperationText())
                    ->label('状态'),
            ])
            ->headerActions([
                // 创建
                SecretAction::createDeviceHasSecret($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Secret $secret */
                        $secret = $this->getOwnerRecord();
                        $can = auth()->user()->can('create_has_secret_secret');

                        return $can && ! $secret->hasSecrets()->count();
                    }),
            ])
            ->actions([
                // 删除
                SecretAction::deleteDeviceHasSecret()
                    ->visible(function (DeviceHasSecret $device_has_secret) {
                        $can = auth()->user()->can('delete_has_secret_secret');

                        return $can && ! $device_has_secret->service()->isDeleted();
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
