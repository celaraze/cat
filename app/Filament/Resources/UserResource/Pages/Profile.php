<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Filament\Widgets\ChangePassword;
use Filament\Resources\Pages\Page;

class Profile extends Page
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.profile';

    protected static ?string $title = '个人档';

    public function mount(): void
    {
        static::authorizeResourceAccess();
    }

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
