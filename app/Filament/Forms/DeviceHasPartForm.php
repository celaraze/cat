<?php

namespace App\Filament\Forms;

use App\Services\DeviceService;
use App\Services\PartService;
use Filament\Forms\Components\Select;

class DeviceHasPartForm
{
    /**
     * 附加配件.
     */
    public static function create(): array
    {
        return [
            //region 选择 配件 part_id
            Select::make('part_id')
                ->label('配件')
                ->options(PartService::pluckOptions())
                ->searchable()
                ->preload()
                ->required(),
            //endregion
        ];
    }

    /**
     * 配件附加到设备.
     */
    public static function createFromPart(): array
    {
        return [
            Select::make('device_id')
                ->options(DeviceService::pluckOptions())
                ->searchable()
                ->label('设备'),
        ];
    }
}
