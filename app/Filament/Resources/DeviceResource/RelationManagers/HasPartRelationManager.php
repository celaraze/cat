<?php

namespace App\Filament\Resources\DeviceResource\RelationManagers;

use App\Filament\Actions\DeviceAction;
use App\Models\DeviceHasPart;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class HasPartRelationManager extends RelationManager
{
    protected static string $relationship = 'hasParts';

    protected static ?string $title = '配件';

    protected static ?string $icon = 'heroicon-m-cpu-chip';

    public function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('part.asset_number')
            ->columns([
                Tables\Columns\TextColumn::make('part.category.name')
                    ->label('分类'),
                Tables\Columns\TextColumn::make('part.asset_number')
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(function (DeviceHasPart $device_has_part) {
                        if ($device_has_part->getAttribute('status') == '附加') {
                            return 'success';
                        } else {
                            return 'danger';
                        }
                    })
                    ->label('状态'),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('操作时间'),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('操作人'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                DeviceAction::deleteDeviceHasPart()
                    ->visible(function (DeviceHasPart $device_has_part) {
                        return ! $device_has_part->service()->isDeleted();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                DeviceAction::createDeviceHasPart($this->getOwnerRecord()),
            ])
            ->emptyStateActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
