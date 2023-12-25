<?php

namespace App\Filament\Forms;

use App\Enums\DeviceHasUserEnum;
use App\Services\UserService;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

class DeviceHasUserForm
{
    /**
     * 分配.
     */
    public static function create(): array
    {
        return [
            Hidden::make('creator_id')
                ->default(auth()->id()),
            Radio::make('status')
                ->options(DeviceHasUserEnum::allStatusText())
                ->label(__('cat/device_has_user.status'))
                ->required(),

            Select::make('user_id')
                ->label(__('cat/device_has_user.user_id'))
                ->options(UserService::pluckOptions())
                ->searchable()
                ->required(),

            TextInput::make('comment')
                ->label(__('cat/device_has_user.comment'))
                ->required(),

            DatePicker::make('expired_at')
                ->label(__('cat/device_has_user.expired_at')),
        ];
    }

    /**
     * 解除.
     */
    public static function delete(): array
    {
        return [
            TextInput::make('delete_comment')
                ->label(__('cat/device_has_user.delete_comment'))
                ->required(),
        ];
    }
}
