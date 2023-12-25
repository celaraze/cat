<?php

namespace App\Filament\Imports;

use App\Models\ConsumableUnit;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ConsumableUnitImporter extends Importer
{
    protected static ?string $model = ConsumableUnit::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/consumable_unit.importer.name_example'))
                ->label(__('cat/consumable_unit.name')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/consumable_unit.importer.import_success', ['success_count' => $import->successful_rows]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/consumable_unit.importer.import_failure', ['failure_count' => $failedRowsCount]);
        }

        return $body;
    }

    public function resolveRecord(): ?ConsumableUnit
    {
        return new ConsumableUnit();
    }
}
