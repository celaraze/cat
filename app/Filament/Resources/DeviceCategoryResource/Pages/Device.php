<?php

namespace App\Filament\Resources\DeviceCategoryResource\Pages;

use App\Filament\Actions\DeviceCategoryAction;
use App\Filament\Resources\DeviceCategoryResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class Device extends ManageRelatedRecords
{
    protected static string $resource = DeviceCategoryResource::class;

    protected static string $relationship = 'devices';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.device');
    }

    public function getTitle(): string|Htmlable
    {
        return __('cat/menu.device');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/device.asset_number')),
            ])
            ->filters([

            ])
            ->headerActions([

            ])
            ->actions([
                // 前往设备详情
                DeviceCategoryAction::toDeviceView(),
            ])
            ->bulkActions([

            ]);
    }
}
