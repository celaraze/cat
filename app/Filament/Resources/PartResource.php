<?php

namespace App\Filament\Resources;

use App\Filament\Actions\PartAction;
use App\Filament\Forms\PartForm;
use App\Filament\Imports\PartImporter;
use App\Filament\Resources\PartResource\Pages\Create;
use App\Filament\Resources\PartResource\Pages\Edit;
use App\Filament\Resources\PartResource\Pages\Index;
use App\Filament\Resources\PartResource\Pages\View;
use App\Filament\Resources\PartResource\RelationManagers\HasPartRelationManager;
use App\Http\Middleware\FilamentLockTab;
use App\Models\Part;
use App\Services\AssetNumberRuleService;
use App\Utils\NotificationUtil;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
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
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class PartResource extends Resource
{
    protected static ?string $model = Part::class;

    protected static ?string $navigationIcon = 'heroicon-m-cpu-chip';

    protected static ?string $modelLabel = '配件';

    protected static ?int $navigationSort = 2;

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
                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->toggleable()
                    ->label('品牌'),
                Tables\Columns\TextColumn::make('category.name')
                    ->searchable()
                    ->toggleable()
                    ->label('分类'),
                Tables\Columns\TextColumn::make('specification')
                    ->searchable()
                    ->toggleable()
                    ->label('规格'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ImportAction::make()
                    ->importer(PartImporter::class)
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('info')
                    ->label('导入'),
                ExportAction::make()
                    ->label('导出'),
                PartAction::createPart(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('资产编号配置')
                        ->form([
                            Select::make('asset_number_rule_id')
                                ->label('规则')
                                ->options(AssetNumberRuleService::pluckOptions())
                                ->required()
                                ->default(AssetNumberRuleService::getAutoRule(Part::class)?->getAttribute('id')),
                            Checkbox::make('is_auto')
                                ->label('自动生成')
                                ->default(AssetNumberRuleService::getAutoRule(Part::class)?->getAttribute('is_auto'))
                        ])
                        ->action(function (array $data) {
                            $data['class_name'] = Part::class;
                            AssetNumberRuleService::setAutoRule($data);
                            NotificationUtil::make(true, '已选择规则');
                        }),
                    Tables\Actions\Action::make('重置资产编号配置')
                        ->action(function () {
                            AssetNumberRuleService::resetAutoRule(Part::class);
                            NotificationUtil::make(true, '已清除所有规则绑定关系');
                        })
                ])
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(PartForm::createOrEditPart());
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
                                        TextEntry::make('category.name')
                                            ->label('分类')
                                    ]),
                                    Group::make([
                                        TextEntry::make('sn')
                                            ->label('序列号'),
                                        TextEntry::make('brand.name')
                                            ->label('品牌'),
                                        TextEntry::make('specification')
                                            ->label('规格'),
                                    ])
                                ])
                        ])
                    ])
            ])->columnSpan(['lg' => 2]),
            Group::make()->schema([
                Section::make()
                    ->schema([
                        ImageEntry::make('image')
                            ->disk('public')
                            ->label('照片')
                    ])
            ])->columnSpan(['lg' => 1]),
        ])->columns(3);
    }

    public static function getRelations(): array
    {
        return [
            HasPartRelationManager::make()
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
