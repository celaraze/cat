<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\ChangePassword;
use Filament\Pages\Page;

class Profile extends Page
{
    protected static string $view = 'filament.pages.profile';

    protected static ?string $title = '个人档';

    protected static bool $shouldRegisterNavigation = false;

    public function getHeaderWidgetsColumns(): int|array
    {
        return 3;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ChangePassword::make(),
        ];
    }
}
