<?php

namespace App\Filament\Resources\DeviceResource\Pages;

use App\Enums\FlowHasFormEnum;
use App\Filament\Actions\FlowHasFormAction;
use App\Filament\Resources\DeviceResource;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Form extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = DeviceResource::class;

    protected static string $relationship = 'forms';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.flow_has_form');
    }

    public static function getNavigationBadge(): ?string
    {
        return self::queryRecord()->forms()->count();
    }

    public function getBreadcrumb(): string
    {
        return __('cat/menu.flow_has_form');
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('node.flow.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/flow_has_form.name')),
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/flow_has_form.created_at')),
                Tables\Columns\TextColumn::make('applicant.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/flow_has_form.applicant')),
                Tables\Columns\TextColumn::make('node.role.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/flow_has_form.current_approver_role_id')),
                Tables\Columns\TextColumn::make('approver.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/flow_has_form.approver')),
                Tables\Columns\TextColumn::make('comment')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/flow_has_form.comment')),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->formatStateUsing(function ($state) {
                        return FlowHasFormEnum::statusText($state);
                    })
                    ->badge()
                    ->color(function ($state) {
                        return FlowHasFormEnum::statusColor($state);
                    })
                    ->label(__('cat/flow_has_form.status')),
            ])
            ->filters([

            ])
            ->headerActions([

            ])
            ->actions([
                FlowHasFormAction::approve()
                    ->visible(function () {
                        return true;
                    }),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ]));
    }
}
