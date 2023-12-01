<?php

namespace App\Filament\Resources;

use App\Filament\Actions\SoftwareAction;
use App\Filament\Forms\SoftwareForm;
use App\Filament\Imports\SoftwareImporter;
use App\Filament\Resources\SoftwareResource\Pages\Create;
use App\Filament\Resources\SoftwareResource\Pages\Edit;
use App\Filament\Resources\SoftwareResource\Pages\Index;
use App\Filament\Resources\SoftwareResource\Pages\View;
use App\Filament\Resources\SoftwareResource\RelationManagers\HasSoftwareRelationManager;
use App\Http\Middleware\FilamentLockTab;
use App\Models\Software;
use App\Services\SoftwareService;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ImportAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class SoftwareResource extends Resource
{
    protected static ?string $model = Software::class;

    protected static ?string $navigationIcon = 'heroicon-m-squares-plus';

    protected static ?string $slug = 'software';

    protected static ?string $modelLabel = '软件';

    protected static ?int $navigationSort = 3;

    protected static ?string $navigationGroup = '信息资产';

    protected static string|array $routeMiddleware = FilamentLockTab::class;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('images')
                    ->label('照片'),
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->toggleable()
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label('名称'),
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->toggleable()
                    ->label('品牌'),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->toggleable()
                    ->label('分类'),
                Tables\Columns\TextColumn::make('sn')
                    ->searchable()
                    ->toggleable()
                    ->label('sn'),
                Tables\Columns\TextColumn::make('specification')
                    ->searchable()
                    ->toggleable()
                    ->label('规格'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                SoftwareAction::createFlowHasFormForDeletingSoftware()
                    ->visible(SoftwareService::isSetDeleteFlow()),
                SoftwareAction::deleteSoftware(),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(SoftwareImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('info')
                    ->label('导入'),
                ExportAction::make()
                    ->label('导出'),
                SoftwareAction::createSoftware(),
                Tables\Actions\ActionGroup::make([
                    SoftwareAction::setAssetNumberRule(),
                    SoftwareAction::resetAssetNumberRule(),
                    SoftwareAction::setSoftwareDeleteFlowId()
                ])
                    ->label('高级')
                    ->icon('heroicon-m-cog-8-tooth')
                    ->button(),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(SoftwareForm::createOrEditSoftware());
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Group::make()->schema([
                Section::make()
                    ->schema([
                        Split::make([
                            Grid::make()
                                ->schema([
                                    Group::make([
                                        TextEntry::make('asset_number')
                                            ->label('资产编号')
                                            ->badge()
                                            ->color('primary'),
                                        TextEntry::make('name')
                                            ->label('名称'),
                                        TextEntry::make('category.name')
                                            ->label('分类'),
                                    ]),
                                    Group::make([
                                        TextEntry::make('sn')
                                            ->label('序列号'),
                                        TextEntry::make('brand.name')
                                            ->label('品牌'),
                                        TextEntry::make('specification')
                                            ->label('规格'),
                                    ]),
                                ]),
                        ]),
                    ]),
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make()
                    ->schema([
                        ImageEntry::make('image')
                            ->disk('public')
                            ->label('照片'),
                    ]),
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            HasSoftwareRelationManager::make(),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'create' => Create::route('/create'),
            'edit' => Edit::route('/{record}/edit'),
            'view' => View::route('/{record}'),
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

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
