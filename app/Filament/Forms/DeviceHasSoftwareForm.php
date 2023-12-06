<?php

namespace App\Filament\Forms;

use App\Services\DeviceService;
use App\Services\SoftwareService;
use Filament\Forms\Components\Select;

class DeviceHasSoftwareForm
{
    /**
     * 附加软件.
     */
    public static function create(): array
    {
        return [
            Select::make('software_id')
                ->label('软件')
                ->options(SoftwareService::pluckOptions())
                ->searchable()
                ->preload(),
        ];
    }

    /**
     * 软件附加到设备.
     */
    public static function createFromSoftware(): array
    {
        return [
            Select::make('device_id')
                ->options(DeviceService::pluckOptions())
                ->searchable()
                ->label('设备'),
        ];
    }
}
