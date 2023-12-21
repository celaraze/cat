<?php

namespace App\Filament\Forms;

use App\Models\DeviceHasSecret;
use App\Models\Secret;
use App\Services\DeviceService;
use App\Services\SecretService;
use Filament\Forms\Components\Select;

class DeviceHasSecretForm
{
    /**
     * 分配.
     */
    public static function createFromSecret(Secret $secret): array
    {
        $device_ids = $secret->hasSecrets()->pluck('device_id')->toArray();

        return [
            Select::make('device_id')
                ->options(DeviceService::pluckOptions('id', $device_ids))
                ->searchable()
                ->label(__('cat.device')),
        ];
    }

    /**
     * 附加.
     */
    public static function create(): array
    {
        $secret_ids = DeviceHasSecret::query()
            ->pluck('secret_id')->toArray();
        $secret_ids = array_unique($secret_ids);

        return [
            Select::make('secret_ids')
                ->options(SecretService::pluckOptions('id', $secret_ids))
                ->multiple()
                ->searchable()
                ->preload()
                ->required()
                ->label(__('cat.secret')),
        ];
    }
}
