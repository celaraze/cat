<?php

namespace App\Filament\Imports;

use App\Models\DeviceCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DeviceCategoryImporter extends Importer
{
    protected static ?string $model = DeviceCategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/device_category.importer.name_example'))
                ->label(__('cat/device_category.name')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/device_category.importer.import_success', ['success_count' => $import->successful_rows]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/device_category.importer.import_failure', ['failure_count' => $failedRowsCount]);
        }

        return $body;
    }

    public function resolveRecord(): ?DeviceCategory
    {
        return new DeviceCategory();
    }
}
