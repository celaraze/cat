<?php

namespace App\Filament\Forms;

use App\Models\Software;
use App\Services\DeviceService;
use App\Services\SoftwareService;
use Filament\Forms\Components\Select;

class DeviceHasSoftwareForm
{
    /**
     * 附加.
     */
    public static function create(): array
    {
        return [
            Select::make('software_ids')
                ->options(SoftwareService::pluckOptions())
                ->multiple()
                ->searchable()
                ->preload()
                ->label(__('cat/device_has_software.software_ids')),
        ];
    }

    /**
     * 分配.
     */
    public static function createFromSoftware(Software $software): array
    {
        $device_ids = $software->hasSoftware()->pluck('device_id')->toArray();

        return [
            Select::make('device_ids')
                ->options(DeviceService::pluckOptions('id', $device_ids))
                ->multiple()
                ->searchable()
                ->label(__('cat/device_has_software.device_ids')),
        ];
    }
}
