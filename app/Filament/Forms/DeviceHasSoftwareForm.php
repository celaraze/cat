<?php

namespace App\Filament\Forms;

use App\Models\Software;
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
            Select::make('software_ids')
                ->options(SoftwareService::pluckOptions())
                ->multiple()
                ->searchable()
                ->preload()
                ->label('软件'),
        ];
    }

    /**
     * 软件附加到设备.
     */
    public static function createFromSoftware(Software $software): array
    {
        $device_ids = $software->hasSoftware()->pluck('device_id')->toArray();

        return [
            Select::make('device_ids')
                ->options(DeviceService::pluckOptions('id', $device_ids))
                ->multiple()
                ->searchable()
                ->label('设备'),
        ];
    }
}
