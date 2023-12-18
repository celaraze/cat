<?php

namespace App\Filament\Resources;

use App\Enums\AssetEnum;
use App\Filament\Actions\SecretAction;
use App\Filament\Forms\SecretForm;
use App\Filament\Resources\SecretResource\Pages\Edit;
use App\Filament\Resources\SecretResource\Pages\HasSecret;
use App\Filament\Resources\SecretResource\Pages\Index;
use App\Filament\Resources\SecretResource\Pages\View;
use App\Models\Device;
use App\Models\Secret;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms\Form;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class SecretResource extends Resource implements HasShieldPermissions
{
    protected static ?string $model = Secret::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = '密钥';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = '资产';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        /* @var Device $record */
        return [
            '名称' => $record->getAttribute('name'),
        ];
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        $navigation_items = [
            Index::class,
            View::class,
            Edit::class,
            HasSecret::class,
        ];
        $secret_service = $page->getWidgetData()['record']->service();
        $can_update_secret = auth()->user()->can('update_secret');
        if ($secret_service->isRetired() || ! $can_update_secret) {
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
            'import',
            'export',
            'retire',
            'create_has_secret',
            'delete_has_secret',
            'view_token',
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema(SecretForm::createOrEdit());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->toggleable()
                    ->label('名称'),
                Tables\Columns\TextColumn::make('site')
                    ->searchable()
                    ->toggleable()
                    ->label('站点'),
                Tables\Columns\TextColumn::make('username')
                    ->searchable()
                    ->toggleable()
                    ->label('账户'),
                Tables\Columns\TextColumn::make('expired_at')
                    ->searchable()
                    ->toggleable()
                    ->label('过期时间'),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->toggleable()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return AssetEnum::statusText($state);
                    })
                    ->color(function ($state) {
                        return AssetEnum::statusColor($state);
                    })
                    ->label('状态'),
            ])
            ->filters([
                //
            ])
            ->actions([
                // 查看密码
                SecretAction::viewToken()
                    ->visible(function () {
                        return auth()->user()->can('view_token_secret');
                    }),
                // 弃用
                SecretAction::retire()
                    ->visible(function (Secret $secret) {
                        return ! $secret->service()->isRetired();
                    }),
            ])
            ->bulkActions([

            ])
            ->headerActions([
                // 新增
                SecretAction::create(),
            ])
            ->heading('密钥');
    }

    public static function getPages(): array
    {
        return [
            'index' => Index::route('/'),
            'view' => View::route('/{record}'),
            'edit' => Edit::route('/{record}/edit'),
            'secrets' => HasSecret::route('/{record}/has_secrets'),
        ];
    }
}
