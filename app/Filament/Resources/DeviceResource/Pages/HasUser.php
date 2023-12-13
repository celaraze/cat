<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceAction;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use App\Models\DeviceHasUser;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasUser extends ManageRelatedRecords
{
    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'hasUsers';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?string $breadcrumb = '用户';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return '用户';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('user.name')
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->color(function (DeviceHasUser $device_user_track) {
                        if ($device_user_track->getAttribute('deleted_at')) {
                            return 'danger';
                        } else {
                            return 'success';
                        }
                    })
                    ->label('用户'),
                Tables\Columns\TextColumn::make('comment')
                    ->searchable()
                    ->toggleable()
                    ->label('分配说明'),
                Tables\Columns\TextColumn::make('delete_comment')
                    ->searchable()
                    ->toggleable()
                    ->label('解除分配说明'),
                Tables\Columns\TextColumn::make('expired_at')
                    ->searchable()
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('到期时间'),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('分配时间'),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->searchable()
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label('解除分配时间'),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return AssetEnum::statusText($state);
                    })
                    ->color(function ($state) {
                        return AssetEnum::statusColor($state);
                    })
                    ->label('状态'),
            ])
            ->filters([

            ])
            ->headerActions([
                // 分配用户
                DeviceAction::createHasUser($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('assign_user_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired && ! $device->service()->isExistHasUser();
                    }),
                // 解除用户
                DeviceAction::deleteHasUser($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('delete_assign_user_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired && $device->service()->isExistHasUser();
                    }),
            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
