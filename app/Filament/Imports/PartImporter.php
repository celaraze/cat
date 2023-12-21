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
                ->example(__('cat.example_part_asset_number'))
                ->label(__('cat.asset_number')),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat.example_part_category'))
                ->label(__('cat.category')),
            ImportColumn::make('sn')
                ->example(__('cat.example_part_sn'))
                ->label(__('cat.sn')),
            ImportColumn::make('specification')
                ->example(__('cat.example_part_specification'))
                ->label(__('cat.specification')),
            ImportColumn::make('image')
                ->example(__('cat.example_part_image'))
                ->label(__('cat.image')),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat.example_brand'))
                ->label(__('cat.brand')),
            ImportColumn::make('description')
                ->example(__('cat.example_part_description'))
                ->label(__('cat.description')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat.import.part_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat.import.part_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?Part
    {
        return new Part();
    }
}
