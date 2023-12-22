<?php

namespace App\Filament\Forms;

use App\Enums\FlowHasNodeEnum;
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
                ->label(__('cat/flow_has_node.name'))
                ->required(),
            Radio::make('type')
                ->label(__('cat/flow_has_node.type'))
                ->options(FlowHasNodeEnum::allTypeText())
                ->default('user')
                ->reactive()
                ->required(),
            Select::make('user_id')
                ->label(__('cat/flow_has_node.user_id'))
                ->options(UserService::pluckOptions())
                ->hidden(fn (callable $get) => $get('type') != 'user'),
            Select::make('role_id')
                ->label(__('cat/flow_has_node.role_id'))
                ->options(RoleService::pluckOptions())
                ->hidden(fn (callable $get) => $get('type') != 'role'),
        ];
    }
}
