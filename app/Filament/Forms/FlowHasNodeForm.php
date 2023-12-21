<?php

namespace App\Filament\Forms;

use App\Services\RoleService;
use App\Services\UserService;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FlowHasNodeForm
{
    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            TextInput::make('name')
                ->label(__('cat.name'))
                ->required(),
            Radio::make('type')
                ->label(__('cat.type'))
                ->options(['user' => __('cat.user'), 'role' => __('cat.role')])
                ->default('user')
                ->reactive()
                ->required(),
            Select::make('user_id')
                ->label(__('cat.user'))
                ->options(UserService::pluckOptions())
                ->hidden(fn (callable $get) => $get('type') != 'user'),
            Select::make('role_id')
                ->label(__('cat.role'))
                ->options(RoleService::pluckOptions())
                ->hidden(fn (callable $get) => $get('type') != 'role'),
        ];
    }
}
