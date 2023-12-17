<?php

namespace App\Filament\Forms;

use App\Models\DeviceHasPart;
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
        $part_ids = DeviceHasPart::query()
            ->pluck('part_id')->toArray();
        $part_ids = array_unique($part_ids);

        return [
            Select::make('part_ids')
                ->options(PartService::pluckOptions('id', $part_ids))
                ->multiple()
                ->searchable()
                ->preload()
                ->required()
                ->label('配件'),
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
