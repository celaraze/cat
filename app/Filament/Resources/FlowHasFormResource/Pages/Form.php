<?php

namespace App\Filament\Resources\FlowHasFormResource\Pages;

use App\Enums\FlowHasNodeEnum;
use App\Filament\Resources\FlowHasFormResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Form extends ManageRelatedRecords
{
    protected static string $resource = FlowHasFormResource::class;

    protected static string $relationship = 'forms';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $title = '记录';

    public static function getNavigationLabel(): string
    {
        return '记录';
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('uuid')
            ->columns([
                Tables\Columns\TextColumn::make('node_name')
                    ->searchable()
                    ->toggleable()
                    ->label('节点说明'),
                Tables\Columns\TextColumn::make('approve_comment')
                    ->searchable()
                    ->toggleable()
                    ->label('审批意见'),
                Tables\Columns\TextColumn::make('approve_user_name')
                    ->searchable()
                    ->toggleable()
                    ->label('审批人'),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label('审批时间'),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->formatStateUsing(function (string $state) {
                        return FlowHasNodeEnum::statusText($state);
                    })
                    ->icon(function (string $state) {
                        return FlowHasNodeEnum::statusIcons($state);
                    })
                    ->color(function (string $state) {
                        return FlowHasNodeEnum::statusColor($state);
                    })
                    ->label('状态'),
            ])
            ->filters([

            ])
            ->headerActions([

            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderBy('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
