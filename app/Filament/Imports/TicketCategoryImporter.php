<?php

namespace App\Filament\Imports;

use App\Models\TicketCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class TicketCategoryImporter extends Importer
{
    protected static ?string $model = TicketCategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/example_ticket_category'))
                ->label(__('cat/name')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/import.ticket_category_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/import.ticket_category_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?TicketCategory
    {
        return new TicketCategory();
    }
}
