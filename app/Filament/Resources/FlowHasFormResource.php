<?php

namespace App\Filament\Resources;

use App\Enums\FlowHasFormEnum;
use App\Filament\Actions\FlowHasFormAction;
use App\Filament\Resources\FlowHasFormResource\Pages\Form;
use App\Filament\Resources\FlowHasFormResource\Pages\Index;
use App\Filament\Resources\FlowHasFormResource\Pages\View;
use App\Models\FlowHasForm;
use App\Models\User;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class FlowHasFormResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = FlowHasForm::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-text';

    protected static ?int $navigationSort = 1;

    protected static ?string $recordTitleAttribute = 'uuid';

    public static function getNavigationGroup(): ?string
    {
        return __('cat/menu.workflow');
    }

    public static function getModelLabel(): string
    {
        return __('cat/menu.flow_has_form');
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Form::class,
        ];

        return $page->generateNavigationItems($navigation_items);
    }

    public static function getPermissionPrefixes(): array
    {
        return [
            'view',
            'view_any',
            'create',
            'update',
            'delete',
            'delete_any',
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->toggleable()
                    ->searchable()
                    ->label(__('cat/flow_has_form.name')),
                Tables\Columns\TextColumn::make('uuid')
                    ->badge()
                    ->color('primary')
                    ->label(__('cat/flow_has_form.uuid')),
                Tables\Columns\TextColumn::make('flow_name')
                    ->toggleable()
                    ->searchable()
                    ->label(__('cat/flow_has_form.flow_name')),
                Tables\Columns\TextColumn::make('applicantUser.name')
                    ->toggleable()
                    ->searchable()
                    ->label(__('cat/flow_has_form.applicant')),
                Tables\Columns\TextColumn::make('type')
                    ->toggleable()
                    ->searchable()
                    ->label(__('cat/flow_has_form.type')),
                Tables\Columns\TextColumn::make('status')
                    ->toggleable()
                    ->searchable()
                    ->formatStateUsing(function (string $state) {
                        return FlowHasFormEnum::statusText($state);
                    })
                    ->icon(function (string $state) {
                        return FlowHasFormEnum::statusIcons($state);
                    })
                    ->iconColor(function (string $state) {
                        return FlowHasFormEnum::statusColor($state);
                    })
                    ->color(function (string $state) {
                        return FlowHasFormEnum::statusColor($state);
                    })
                    ->label(__('cat/flow_has_form.status')),
            ])
            ->filters([

            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                // 创建
                FlowHasFormAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_flow::has::form');
                    }),
            ])
            ->heading(__('cat/menu.flow_has_form'));
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'forms' => Form::route('/{record}/forms'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Group::make()->schema([
                Section::make()->schema([
                    TextEntry::make('uuid')
                        ->hintActions([
                            Action::make(__('cat/flow_has_form.action.snapshot_helper'))
                                ->icon('heroicon-s-camera')
                                ->color('warning')
                                ->visible(function (FlowHasForm $form_has_form) {
                                    return $form_has_form->service()->isFinished();
                                }),
                            FlowHasFormAction::approve()
                                ->visible(function (FlowHasForm $flow_has_form) {
                                    // 根据表单状态判断是否显示审批按钮
                                    $current_approve_user_id = $flow_has_form->getAttribute('current_approve_user_id');
                                    $current_approve_role_id = $flow_has_form->getAttribute('current_approve_role_id');
                                    if (! $flow_has_form->service()->isFinished()) {
                                        // 根据表单当前审批人判断是否显示审批按钮
                                        if ($current_approve_user_id == auth()->id()) {
                                            return true;
                                        }
                                        // 根据表单当前审批角色判断是否显示审批按钮
                                        $user = auth()->user();
                                        /* @var User $user */
                                        if ($user->hasRole($current_approve_role_id)) {
                                            return true;
                                        }
                                    }

                                    return false;
                                }),
                        ])
                        ->badge()
                        ->label(__('cat/flow_has_form.uuid')),
                    TextEntry::make('status')
                        ->formatStateUsing(function (string $state) {
                            return FlowHasFormEnum::statusText($state);
                        })
                        ->icon(function (string $state) {
                            return FlowHasFormEnum::statusIcons($state);
                        })
                        ->iconColor(function (string $state) {
                            return FlowHasFormEnum::statusColor($state);
                        })
                        ->color(function (string $state) {
                            return FlowHasFormEnum::statusColor($state);
                        })
                        ->label(__('cat/flow_has_form.status')),
                    TextEntry::make('type')
                        ->label(__('cat/flow_has_form.type')),
                    TextEntry::make('flow_name')
                        ->hintActions([
                            Action::make(__('cat/flow_has_form.action.flow_deleted_helper'))
                                ->icon('heroicon-m-information-circle')
                                ->color('warning')
                                ->visible(function (FlowHasForm $flow_has_form) {
                                    return ! $flow_has_form->service()->isExistFlow();
                                }),
                        ])
                        ->label(__('cat/flow_has_form.flow_name')),
                    TextEntry::make('name')
                        ->label(__('cat/flow_has_form.name')),
                ]),
            ])->columnSpan(['lg' => 1]),
            Group::make()->schema([
                ViewEntry::make('progress')
                    ->view('cat.infolists.entries.flow-progress'),
            ])->columnSpan(['lg' => 1]),
        ]);
    }
}
