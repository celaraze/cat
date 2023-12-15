<?php

namespace App\Filament\Forms;

use App\Models\Secret;
use App\Services\DeviceService;
use App\Services\SecretService;
use Filament\Forms\Components\Select;

class DeviceHasSecretForm
{
    /**
     * 密钥附加到设备.
     */
    public static function createFromSecret(Secret $secret): array
    {
        $device_ids = $secret->hasSecrets()->pluck('device_id')->toArray();

        return [
            Select::make('device_id')
                ->options(DeviceService::pluckOptions('id', $device_ids))
                ->searchable()
                ->label('设备'),
        ];
    }

    /**
     * 附加密钥.
     */
    public static function create(): array
    {
        return [
            Select::make('secret_ids')
                ->options(SecretService::pluckOptions())
                ->multiple()
                ->searchable()
                ->preload()
                ->required()
                ->label('密钥'),
        ];
    }
}
