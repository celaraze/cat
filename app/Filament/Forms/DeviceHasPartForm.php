<?php

namespace App\Filament\Forms;

use App\Models\Part;
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
            Select::make('part_id')
                ->label('配件')
                ->options(PartService::pluckOptions())
                ->searchable()
                ->preload()
                ->required(),
        ];
    }

    /**
     * 配件附加到设备.
     */
    public static function createFromPart(Part $part): array
    {
        $device_ids = $part->hasParts()->pluck('device_id')->toArray();

        return [
            Select::make('device_id')
                ->options(DeviceService::pluckOptions('id', $device_ids))
                ->searchable()
                ->label('设备'),
        ];
    }
}
