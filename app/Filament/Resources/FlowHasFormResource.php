<?php

namespace App\Filament\Resources;

use App\Filament\Actions\FlowAction;
use App\Filament\Resources\FlowHasFormResource\Pages\Create;
use App\Filament\Resources\FlowHasFormResource\Pages\Form;
use App\Filament\Resources\FlowHasFormResource\Pages\Index;
use App\Filament\Resources\FlowHasFormResource\Pages\View;
use App\Models\FlowHasForm;
use App\Utils\FlowHasFormUtil;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FlowHasFormResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = FlowHasForm::class;

    protected static ?string $navigationIcon = 'heroicon-s-document-text';

    protected static ?string $modelLabel = '表单';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = '工作流';

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Index::class,
            View::class,
            Form::class,
        ]);
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
                    ->label('表单名称')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('唯一编码')
                    ->toggleable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\TextColumn::make('flow_name')
                    ->label('流程名称')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('applicantUser.name')
                    ->label('申请人')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('当前审批')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('formStatusText')
                    ->label('状态')
                    ->toggleable()
                    ->icon(function ($state) {
                        return FlowHasFormUtil::formStatusTextIcons($state);
                    })
                    ->color(function ($state) {
                        return FlowHasFormUtil::formStatusTextColors($state);
                    }),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                // 查看
                Tables\Actions\ViewAction::make()
                    ->visible(function () {
                        return auth()->user()->can('view_flow::has::form');
                    }),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                // 创建
                FlowAction::createHasForm()
                    ->visible(function () {
                        return auth()->user()->can('create_flow::has::form');
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'create' => Create::route('/create'),
            'view' => View::route('/{record}'),
            'forms' => Form::route('/{record}/forms'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
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
                    TextEntry::make('flow_name')
                        ->label('流程名称'),
                    TextEntry::make('name')
                        ->label('表单名称'),
                    TextEntry::make('uuid')
                        ->label('唯一编码'),
                ]),
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make()->schema([
                    TextEntry::make('formStatusText')
                        ->icon(function (string $state) {
                            return FlowHasFormUtil::formStatusTextIcons($state);
                        })
                        ->color(function (string $state) {
                            return FlowHasFormUtil::formStatusTextColors($state);
                        })
                        ->label(''),
                ]),
                Section::make()->schema([
                    TextEntry::make('type')
                        ->label('当前审核人'),
                ]),
            ])->columnSpan(['lg' => 1]),
            Group::make()->schema([
                ViewEntry::make('progress')
                    ->view('filament.infolists.entries.flow-progress'),
            ])->columnSpan(['lg' => 3]),
        ])->columns(3);
    }
}
