<?php

namespace App\Filament\Forms;

use App\Services\DeviceService;
use Filament\Forms\Components\Select;

class DeviceHasSoftwareForm
{
    /**
     * 设备附加软件.
     */
    public static function create(): array
    {
        return [
            Select::make('device_id')
                ->options(DeviceService::pluckOptions())
                ->searchable()
                ->label('设备'),
        ];
    }
}
