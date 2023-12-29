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
                ->example(__('cat/device.importer.asset_number_example'))
                ->label(__('cat/device.asset_number')),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/device.importer.category_example'))
                ->label(__('cat/device.category')),
            ImportColumn::make('name')
                ->example(__('cat/device.importer.name_example'))
                ->label(__('cat/device.name')),
            ImportColumn::make('sn')
                ->requiredMapping()
                ->example(__('cat/device.importer.sn_example'))
                ->label(__('cat/device.sn')),
            ImportColumn::make('specification')
                ->requiredMapping()
                ->example(__('cat/device.importer.specification_example'))
                ->label(__('cat/device.specification')),
            ImportColumn::make('image')
                ->example(__('cat/device.importer.image_example'))
                ->label(__('cat/device.image')),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/device.importer.brand_example'))
                ->label(__('cat/device.brand')),
            ImportColumn::make('description')
                ->example(__('cat/device.importer.description_example'))
                ->label(__('cat/device.description')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/device.importer.import_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/device.importer.import_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?Device
    {
        return new Device();
    }
}
