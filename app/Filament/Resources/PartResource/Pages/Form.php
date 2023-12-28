<?php

namespace App\Filament\Resources\PartResource\Pages;

use App\Enums\FlowHasFormEnum;
use App\Filament\Actions\FlowHasFormAction;
use App\Filament\Resources\PartResource;
use App\Models\FlowHasForm;
use App\Traits\ManageRelatedRecords\QueryRecordByUrl;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class Form extends ManageRelatedRecords
{
    use QueryRecordByUrl;

    protected static string $resource = PartResource::class;

    protected static string $relationship = 'forms';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected ?string $heading = ' ';

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.flow_has_form');
    }

    public static function getNavigationBadge(): ?string
    {
        return self::queryRecord()->forms()->select('uuid')->distinct()->get()->count();
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
                Tables\Columns\TextColumn::make('created_at')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/flow_has_form.created_at')),
                Tables\Columns\TextColumn::make('node.flow.name')
                    ->searchable()
                    ->toggleable()
                    ->label(__('cat/flow_has_form.name')),
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
                    ->icon(function ($state) {
                        return FlowHasFormEnum::statusIcons($state);
                    })
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
                    ->visible(function (FlowHasForm $flow_has_form) {
                        $is_completed = $flow_has_form->service()->isCompleted();
                        $is_processed = $flow_has_form->service()->isProcessed();
                        $has_role = auth()->user()->hasRole($flow_has_form->node->role_id);
                        $can = auth()->user()->can('process_flow_has_form_device');

                        return ! $is_completed && ! $is_processed && $has_role && $can;
                    }),
            ])
            ->bulkActions([

            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->orderByDesc('created_at')
                ->withoutGlobalScopes([
                    SoftDeletingScope::class,
                ])
            )
            ->defaultGroup('uuid');
    }
}
