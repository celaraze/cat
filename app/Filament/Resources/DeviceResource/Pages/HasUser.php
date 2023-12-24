<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Enums\AssetEnum;
use App\Filament\Actions\DeviceHasUserAction;
use App\Filament\Resources\DeviceResource;
use App\Models\Device;
use App\Models\DeviceHasUser;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn\TextColumnSize;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasUser extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'hasUsers';

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.user');
    }

    public static function getNavigationBadge(): ?string
    {
        return self::queryRecord()->users()->first()?->getAttribute('name');
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'success';
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.user');
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
                    ->label(__('cat/device_has_user.name')),
                Tables\Columns\TextColumn::make('comment')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device_has_user.comment')),
                Tables\Columns\TextColumn::make('delete_comment')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device_has_user.delete_comment')),
                Tables\Columns\TextColumn::make('expired_at')
                    ->searchable()
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label(__('cat/device_has_user.expired_at')),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label(__('cat/device_has_user.created_at')),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->searchable()
                    ->toggleable()
                    ->size(TextColumnSize::ExtraSmall)
                    ->label(__('cat/device_has_user.deleted_at')),
                Tables\Columns\TextColumn::make('creator.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device_has_user.creator')),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return AssetEnum::statusText($state);
                    })
                    ->color(function ($state) {
                        return AssetEnum::statusColor($state);
                    })
                    ->label(__('cat/device_has_user.status')),
            ])
            ->filters([

            ])
            ->headerActions([
                // 分配用户
                DeviceHasUserAction::create($this->getOwnerRecord())
                    ->visible(function () {
                        /* @var Device $device */
                        $device = $this->getOwnerRecord();
                        $can = auth()->user()->can('assign_user_device');
                        $is_retired = $device->service()->isRetired();

                        return $can && ! $is_retired && ! $device->service()->isExistHasUser();
                    }),
                // 解除用户
                DeviceHasUserAction::delete($this->getOwnerRecord())
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
