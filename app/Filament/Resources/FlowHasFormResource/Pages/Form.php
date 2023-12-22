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

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat.menu.flow_has_form_record');
    }

    public function getBreadcrumb(): string
    {
        return __('cat.menu.flow_has_form_record');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('uuid')
            ->columns([
                Tables\Columns\TextColumn::make('node_name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.flow_has_form.node_name')),
                Tables\Columns\TextColumn::make('approve_comment')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.flow_has_form.approve_comment')),
                Tables\Columns\TextColumn::make('approve_user_name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.flow_has_form.approve_user_name')),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat.flow_has_form.created_at')),
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
                    ->label(__('cat.flow_has_form.status')),
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
