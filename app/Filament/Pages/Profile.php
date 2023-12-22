<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ChangeAvatar;
use App\Filament\Widgets\ChangePassword;
use Filament\Pages\Page;

class Profile extends Page
{
    protected static string $view = 'cat.pages.profile';

    protected static bool $shouldRegisterNavigation = false;

    public static function getNavigationLabel(): string
    {
        return __('cat/menu.profile');
    }

    public function getHeaderWidgetsColumns(): int|array
    {
        return 3;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ChangePassword::make(),
            ChangeAvatar::make(),
        ];
    }
}
