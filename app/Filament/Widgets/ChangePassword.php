<?php

namespace App\Filament\Widgets;

use App\Filament\Actions\UserAction;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Widgets\Widget;

class ChangePassword extends Widget implements HasActions, HasForms
{
    use InteractsWithActions;
    use InteractsWithForms;

    protected static string $view = 'filament.resources.widgets.change-password';

    protected static ?int $sort = 1;

    public function changePasswordAction(): Action
    {
        return UserAction::changePassword()
            // DEMO 模式不允许修改密码
            ->visible(! config('app.demo_mode'));
    }

    public function getDescription(): string
    {
        return __('cat.widget.change_password_description');
    }
}
