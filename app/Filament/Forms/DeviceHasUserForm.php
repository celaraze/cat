<?php

namespace App\Filament\Forms;

use App\Services\UserService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class DeviceHasUserForm
{
    /**
     * 创建.
     */
    public static function create(): array
    {
        return [
            Radio::make('status')
                ->options([1 => '使用', 2 => '借用'])
                ->label('分配方式')
                ->required(),

            Select::make('user_id')
                ->label('使用者')
                ->options(UserService::pluckOptions())
                ->searchable()
                ->required(),

            TextInput::make('comment')
                ->label('说明')
                ->required(),

            DatePicker::make('expired_at')
                ->label('过期时间'),
        ];
    }

    /**
     * 解除设备使用者.
     */
    public static function delete(): array
    {
        return [
            TextInput::make('delete_comment')
                ->label('解除说明')
                ->required(),
        ];
    }
}
