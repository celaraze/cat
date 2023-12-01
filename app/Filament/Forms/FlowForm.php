<?php

namespace App\Filament\Forms;

use App\Services\RoleService;
use App\Services\UserService;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class FlowForm
{
    /**
     * 创建或编辑流程的表单.
     */
    public static function createFlow(): array
    {
        return [
            TextInput::make('name')
                ->label('名称')
                ->required()
                ->unique(),
        ];
    }

    /**
     * 创建流程节点的表单.
     */
    public static function createNode(): array
    {
        return [
            TextInput::make('name')
                ->label('名称')
                ->required(),
            Radio::make('type')
                ->label('类型')
                ->options(['user' => '用户', 'role' => '角色'])
                ->default('user')
                ->reactive()
                ->required(),
            Select::make('user_id')
                ->label('用户')
                ->options(UserService::pluckOptions())
                ->hidden(fn (callable $get) => $get('type') != 'user'),
            Select::make('role_id')
                ->label('角色')
                ->options(RoleService::pluckOptions())
                ->hidden(fn (callable $get) => $get('type') != 'role'),
        ];
    }
}
