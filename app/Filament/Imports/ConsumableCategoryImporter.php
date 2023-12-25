<?php

namespace App\Filament\Imports;

use App\Models\ConsumableCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ConsumableCategoryImporter extends Importer
{
    protected static ?string $model = ConsumableCategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/consumable_category.importer.name_example'))
                ->label(__('cat/consumable_category.name')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/consumable_category.importer.import_success', ['success_count' => $import->successful_rows]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/consumable_category.importer.import_failure', ['failure_count' => $failedRowsCount]);
        }

        return $body;
    }

    public function resolveRecord(): ?ConsumableCategory
    {
        return new ConsumableCategory();
    }
}
