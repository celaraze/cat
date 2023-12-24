<?php

namespace App\Filament\Widgets;

use App\Filament\Actions\UserAction;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;

class ChangeAvatar extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string $view = 'cat.widgets.change-avatar';

    protected static ?int $sort = 1;

    public function changeAvatarAction(): Action
    {
        return UserAction::changeAvatar();
    }

    public function getDescription(): string
    {
        return __('cat/profile.widget.change_avatar_description');
    }
}
