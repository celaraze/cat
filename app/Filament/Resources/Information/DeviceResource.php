<?php

namespace App\Filament\Resources\Information;

use App\Filament\Actions\Imformation\DeviceAction;
use App\Filament\Actions\ImportAction;
use App\Filament\Forms\DeviceForm;
use App\Filament\Imports\DeviceImporter;
use App\Filament\Resources\Information\DeviceResource\Pages;
use App\Filament\Resources\Information\DeviceResource\RelationManagers\HasPartRelationManager;
use App\Filament\Resources\Information\DeviceResource\RelationManagers\HasSoftwareRelationManager;
use App\Filament\Resources\Information\DeviceResource\RelationManagers\HasUserRelationManager;
use App\Http\Middleware\FilamentLockTab;
use App\Models\Information\Device;
use App\Services\AssetNumberRuleService;
use App\Services\Information\DeviceCategoryService;
use App\Utils\NotificationUtil;
use Exception;
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
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use pxlrbt\FilamentExcel\Actions\Tables\ExportAction;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-s-server';

    protected static ?string $modelLabel = '设备';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationGroup = '信息资产';

    protected static string|array $routeMiddleware = FilamentLockTab::class;

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('照片')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('asset_number')
                    ->searchable()
                    ->label('资产编号'),
                Tables\Columns\TextColumn::make('name')
                    ->label('名称')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('品牌')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('分类')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('users.name')
                    ->searchable()
                    ->label('管理者')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('specification')
                    ->searchable()
                    ->label('规格')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->multiple()
                    ->options(DeviceCategoryService::pluckOptions())
                    ->label('分类'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\ActionGroup::make([
                    // 分配管理者
                    DeviceAction::createDeviceHasUser()
                        ->visible(function (Device $device) {
                            return !$device->hasUsers()->count();
                        }),
                    // 解除管理者
                    DeviceAction::deleteDeviceHasUser()
                        ->visible(function (Device $device) {
                            return $device->hasUsers()->count();
                        }),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([

            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('123')
                    ->form([
                        Select::make('user_id'),
                    ])
                    ->action(function (array $data, Collection $records) {
                        dd($data, $records);
                    })
            ])
            ->headerActions([
                ImportAction::make(new DeviceImporter()),
                ExportAction::make()->label('导出'),
                DeviceAction::createDevice(),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\Action::make('资产编号配置')
                        ->form([
                            Select::make('asset_number_rule_id')
                                ->label('规则')
                                ->options(AssetNumberRuleService::pluckOptions())
                                ->required()
                                ->default(AssetNumberRuleService::getAutoRule(Device::class)?->getAttribute('id')),
                            Checkbox::make('is_auto')
                                ->label('自动生成')
                                ->default(AssetNumberRuleService::getAutoRule(Device::class)?->getAttribute('is_auto'))
                        ])
                        ->action(function (array $data) {
                            $data['class_name'] = Device::class;
                            AssetNumberRuleService::setAutoRule($data);
                            NotificationUtil::make(true, '已选择规则');
                        }),
                    Tables\Actions\Action::make('重置资产编号配置')
                        ->action(function () {
                            AssetNumberRuleService::resetAutoRule(Device::class);
                            NotificationUtil::make(true, '已清除所有规则绑定关系');
                        })
                ]),
            ]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(DeviceForm::createOrEditDevice());
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
            HasUserRelationManager::class,
            HasPartRelationManager::class,
            HasSoftwareRelationManager::class,
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

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }
}
