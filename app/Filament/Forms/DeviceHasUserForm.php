<?php

namespace App\Filament\Forms;

use App\Services\UserService;
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
            //region 选择 管理者 user_id
            Select::make('user_id')
                ->label('管理者')
                ->options(UserService::pluckOptions())
                ->searchable()
                ->required(),
            //endregion

            //region 文本 说明 comment
            TextInput::make('comment')
                ->label('说明')
                ->required(),
            //endregion
        ];
    }

    /**
     * 解除设备使用者.
     */
    public static function delete(): array
    {
        return [
            //region 文本 解除说明 delete_comment
            TextInput::make('delete_comment')
                ->label('解除说明')
                ->required(),
            //endregion
        ];
    }
}
