<?php

namespace App\Filament\Imports;

use App\Models\Consumable;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ConsumableImporter extends Importer
{
    protected static ?string $model = Consumable::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/consumable.importer.name_example'))
                ->label(__('cat/consumable.name')),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/consumable.importer.category_example'))
                ->label(__('cat/consumable.category')),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/consumable.importer.brand_example'))
                ->label(__('cat/consumable.brand')),
            ImportColumn::make('unit')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/consumable.importer.unit_example'))
                ->label(__('cat/consumable.unit')),
            ImportColumn::make('specification')
                ->requiredMapping()
                ->example(__('cat/consumable.importer.specification_example'))
                ->label(__('cat/consumable.specification')),
            ImportColumn::make('description')
                ->example(__('cat/consumable.importer.description_example'))
                ->label(__('cat/consumable.description')),
            ImportColumn::make('image')
                ->example(__('cat/consumable.importer.image_example'))
                ->label(__('cat/consumable.image')),

        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/consumable.importer.import_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/consumable.importer.import_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?Consumable
    {
        return new Consumable();
    }
}
