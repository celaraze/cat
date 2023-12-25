<?php

namespace App\Filament\Imports;

use App\Models\Brand;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class BrandImporter extends Importer
{
    protected static ?string $model = Brand::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/brand.importer.name_example'))
                ->label(__('cat/brand.name')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/brand.importer.import_success', ['success_count' => $import->successful_rows]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/brand.importer.import_failure', ['failure_count' => $failedRowsCount]);
        }

        return $body;
    }

    public function resolveRecord(): ?Brand
    {
        return new Brand();
    }
}
