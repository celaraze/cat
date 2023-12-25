<?php

namespace App\Filament\Imports;

use App\Models\Part;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PartImporter extends Importer
{
    protected static ?string $model = Part::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('asset_number')
                ->requiredMapping()
                ->example(__('cat/part.importer.asset_number_example'))
                ->label(__('cat/part.asset_number')),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/part.importer.name_example'))
                ->label(__('cat/part.category')),
            ImportColumn::make('sn')
                ->example(__('cat/part.importer.sn_example'))
                ->label(__('cat/part.sn')),
            ImportColumn::make('specification')
                ->example(__('cat/part.importer.specification_example'))
                ->label(__('cat/part.specification')),
            ImportColumn::make('image')
                ->example(__('cat/part.importer.image_example'))
                ->label(__('cat/part.image')),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/part.importer.brand_example'))
                ->label(__('cat/part.brand')),
            ImportColumn::make('description')
                ->example(__('cat/part.importer.description_example'))
                ->label(__('cat/part.description')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/part.importer.import_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/part.importer.import_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?Part
    {
        return new Part();
    }
}
