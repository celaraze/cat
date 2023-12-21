<?php

namespace App\Filament\Imports;

use App\Models\Device;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DeviceImporter extends Importer
{
    protected static ?string $model = Device::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('asset_number')
                ->requiredMapping()
                ->example(__('cat.example_device_asset_number'))
                ->label(__('cat.asset_number')),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat.example_device_category'))
                ->label(__('cat.category')),
            ImportColumn::make('name')
                ->example(__('cat.example_device_name'))
                ->label(__('cat.name')),
            ImportColumn::make('sn')
                ->requiredMapping()
                ->example(__('cat.example_device_sn'))
                ->label(__('cat.sn')),
            ImportColumn::make('specification')
                ->requiredMapping()
                ->example(__('cat.example_device_specification'))
                ->label(__('cat.specification')),
            ImportColumn::make('image')
                ->example(__('cat.example_device_image'))
                ->label(__('cat.image')),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat.example_brand'))
                ->label(__('cat.brand')),
            ImportColumn::make('description')
                ->example(__('cat.example_device_description'))
                ->label(__('cat.description')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat.import.device_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat.import.device_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?Device
    {
        return new Device();
    }
}
