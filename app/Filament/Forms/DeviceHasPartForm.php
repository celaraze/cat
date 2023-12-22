<?php

namespace App\Filament\Forms;

use App\Models\Device;
use App\Models\DeviceHasPart;
use App\Models\Part;
use App\Services\DeviceService;
use App\Services\PartService;
use Exception;
use Filament\Forms\Components\Select;

class DeviceHasPartForm
{
    /**
     * 附加.
     *
     * @throws Exception
     */
    public static function create($model): ?array
    {
        if ($model instanceof Device) {
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
                    ->label(__('cat/device_has_part.part_ids')),
            ];
        }

        if ($model instanceof Part) {
            $device_ids = $model->hasParts()->pluck('device_id')->toArray();

            return [
                Select::make('device_id')
                    ->options(DeviceService::pluckOptions('id', $device_ids))
                    ->searchable()
                    ->label(__('cat/device_has_part.device_id')),
            ];
        }

        throw new Exception('不正确的按钮传参方式。');
    }
}
