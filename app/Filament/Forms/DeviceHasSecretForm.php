<?php

namespace App\Filament\Forms;

use App\Models\Device;
use App\Models\DeviceHasSecret;
use App\Models\Secret;
use App\Services\DeviceService;
use App\Services\SecretService;
use Exception;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;

class DeviceHasSecretForm
{
    /**
     * 附加.
     *
     * @throws Exception
     */
    public static function create($model): array
    {
        if ($model instanceof Device) {
            $secret_ids = DeviceHasSecret::query()
                ->pluck('secret_id')->toArray();
            $secret_ids = array_unique($secret_ids);

            return [
                Hidden::make('creator_id')
                    ->default(auth()->id()),
                Hidden::make('status')
                    ->default(0),
                Select::make('secret_ids')
                    ->options(SecretService::pluckOptions('id', $secret_ids))
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label(__('cat/device_has_secret.secret_ids')),
            ];
        }

        if ($model instanceof Secret) {
            $device_ids = $model->hasSecrets()->pluck('device_id')->toArray();

            return [
                Hidden::make('creator_id')
                    ->default(auth()->id()),
                Hidden::make('status')
                    ->default(0),
                Select::make('device_id')
                    ->options(DeviceService::pluckOptions('id', $device_ids))
                    ->searchable()
                    ->label(__('cat/device_has_secret.device_id')),
            ];
        }

        throw new Exception(__('cat/device_has_secret.form.model_error'));
    }
}
