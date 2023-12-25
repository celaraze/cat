<?php

namespace App\Filament\Imports;

use App\Models\Software;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SoftwareImporter extends Importer
{
    protected static ?string $model = Software::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('asset_number')
                ->requiredMapping()
                ->example(__('cat/software.importer.asset_number_example'))
                ->label(__('cat/software.asset_number')),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/software.importer.category_example'))
                ->label(__('cat/software.category')),
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/software.importer.name_example'))
                ->label(__('cat/software.name')),
            ImportColumn::make('sn')
                ->example(__('cat/software.importer.sn_example'))
                ->label(__('cat/software.sn')),
            ImportColumn::make('specification')
                ->example(__('cat/software.importer.specification_example'))
                ->label(__('cat/software.specification')),
            ImportColumn::make('max_license_count')
                ->requiredMapping()
                ->rules(['numeric', 'min:0'])
                ->example(0)
                ->label(__('cat/software.max_license_count')),
            ImportColumn::make('image')
                ->example(__('cat/software.importer.image_example'))
                ->label(__('cat/software.image')),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/software.importer.brand_example'))
                ->label(__('cat/software.brand')),
            ImportColumn::make('description')
                ->example(__('cat/software.importer.description_example'))
                ->label(__('cat/software.description')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/software.importer.import_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/software.importer.import_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?Software
    {
        return new Software();
    }
}
