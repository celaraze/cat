<?php

namespace App\Filament\Forms;

use App\Models\Device;
use App\Models\Software;
use App\Services\DeviceService;
use App\Services\SoftwareService;
use Exception;
use Filament\Forms\Components\Select;

class DeviceHasSoftwareForm
{
    /**
     * 附加.
     *
     * @throws Exception
     */
    public static function create($model): array
    {
        if ($model instanceof Device) {
            return [
                Select::make('software_ids')
                    ->options(SoftwareService::pluckOptions())
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->label(__('cat/device_has_software.software_ids')),
            ];
        }

        if ($model instanceof Software) {
            $device_ids = $model->hasSoftware()->pluck('device_id')->toArray();

            return [
                Select::make('device_ids')
                    ->options(DeviceService::pluckOptions('id', $device_ids))
                    ->multiple()
                    ->searchable()
                    ->label(__('cat/device_has_software.device_ids')),
            ];
        }

        throw new Exception(__('cat/device_has_software.form.model_error'));
    }
}
