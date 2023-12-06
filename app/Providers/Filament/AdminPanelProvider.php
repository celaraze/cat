<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\StatsOverviewWidget;
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
            ])
            ->widgets([
                StatsOverviewWidget::class,
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
            ->breadcrumbs(false)
            ->plugins([
                FilamentShieldPlugin::make(),
                ThemesPlugin::make(),
                FilamentBackgroundsPlugin::make(),
            ])
            ->brandName('☕️ CAT')
            ->favicon(asset('images/logo.png'))
            ->topNavigation()
            ->maxContentWidth('full')
            ->userMenuItems([
                MenuItem::make()
                    ->label('个人档')
                    ->url('/profile')
                    ->icon('heroicon-o-lock-open'),
                MenuItem::make()
                    ->label('去 Github 为作者点赞 🌟')
                    ->url('https://github.com/celaraze/cat')
                    ->openUrlInNewTab()
                    ->icon('heroicon-s-code-square'),
                MenuItem::make()
                    ->label('官方文档')
                    ->url('https://github.com/celaraze/cat/wiki')
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-book-open'),
                MenuItem::make()
                    ->label('官方用户群')
                    ->url('http://qm.qq.com/cgi-bin/qm/qr?_wv=1027&k=oSXcaCdY4u5iIEQj43J2GsDk_PygRR2G&authKey=atvXMk1ZoXRwuuNzMLY7852APIHfnBp3cA4fu7oFui7MWRSCrg2EafCAI%2B9akAPa&noverify=0&group_code=1016567640')
                    ->openUrlInNewTab()
                    ->icon('heroicon-s-user-group'),
            ]);
    }
}
