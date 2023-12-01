<?php

namespace App\Filament\Resources;

use App\Filament\Actions\FlowAction;
use App\Filament\Resources\FlowHasFormResource\Pages;
use App\Filament\Resources\FlowHasFormResource\RelationManagers\FormRelationManager;
use App\Models\FlowHasForm;
use App\Utils\FlowHasFormUtil;
use Filament\Forms\Form;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FlowHasFormResource extends Resource
{
    protected static ?string $model = FlowHasForm::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = '表单';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = '工作流';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

            ]);
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
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                FlowAction::createHasForm(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FormRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\Index::route('/'),
            'create' => Pages\Create::route('/create'),
            'edit' => Pages\Edit::route('/{record}/edit'),
            'view' => Pages\View::route('/{record}'),
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
