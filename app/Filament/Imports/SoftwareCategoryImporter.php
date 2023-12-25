<?php

namespace App\Filament\Imports;

use App\Models\SoftwareCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SoftwareCategoryImporter extends Importer
{
    protected static ?string $model = SoftwareCategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/software_category.importer.name_example'))
                ->label(__('cat/software_category.name')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/software_category.importer.import_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/software_category.importer.import_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?SoftwareCategory
    {
        return new SoftwareCategory();
    }
}
