<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Hasnayeen\Themes\Http\Middleware\SetTheme;
use Hasnayeen\Themes\ThemesPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('')
            ->login()
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([

            ])
            ->databaseNotifications()
            ->navigationGroups([
                '资产',
                '工作流',
                '基础数据',
                '日志',
                '系统设置',
            ])
            ->widgets([

            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                SetTheme::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->breadcrumbs(true)
            ->plugins([
                FilamentShieldPlugin::make(),
                ThemesPlugin::make(),
                FilamentBackgroundsPlugin::make(),
            ])
            ->brandName('☕️ CAT')
            ->favicon(asset('images/logo.png'))
            ->topNavigation()
            ->maxContentWidth('full')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->userMenuItems([
                MenuItem::make()
                    ->label(__('cat.profile'))
                    ->url('/profile')
                    ->icon('heroicon-m-identification'),
                MenuItem::make()
                    ->label(__('cat.menu.github_helper'))
                    ->url('https://github.com/celaraze/cat')
                    ->openUrlInNewTab()
                    ->icon('heroicon-s-star'),
                MenuItem::make()
                    ->label(__('cat.menu.official_docs'))
                    ->url('https://github.com/celaraze/cat/wiki')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-book-open'),
                MenuItem::make()
                    ->label(__('cat.menu.official_community'))
                    ->url('https://pd.qq.com/s/sknbyfnh')
                    ->openUrlInNewTab()
                    ->icon('heroicon-s-user-group'),
            ]);
    }
}
