<?php

namespace App\Filament\Resources;

use App\Filament\Actions\FlowAction;
use App\Filament\Forms\FlowForm;
use App\Filament\Resources\FlowResource\Pages\Create;
use App\Filament\Resources\FlowResource\Pages\Edit;
use App\Filament\Resources\FlowResource\Pages\Index;
use App\Filament\Resources\FlowResource\Pages\Node;
use App\Filament\Resources\FlowResource\Pages\View;
use App\Models\Flow;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FlowResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Flow::class;

    protected static ?string $navigationIcon = 'heroicon-m-bars-arrow-down';

    protected static ?string $modelLabel = '流程';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = '工作流';

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
            Node::class,
        ];
        $can_update_flow = auth()->user()->can('update_flow');
        if (! $can_update_flow) {
            unset($navigation_items[2]);
        }

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

    public static function form(Form $form): Form
    {
        return $form
            ->schema(FlowForm::create());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('名称'),
                Tables\Columns\TextColumn::make('tag')
                    ->label('唯一标识'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([

            ])
            ->bulkActions([

            ])
            ->emptyStateActions([

            ])
            ->headerActions([
                // 创建
                FlowAction::create()
                    ->visible(function () {
                        return auth()->user()->can('create_flow');
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'create' => Create::route('/create'),
            'edit' => Edit::route('/{record}/edit'),
            'nodes' => Node::route('/{record}/nodes'),
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
}
