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
                ->example(__('cat/example_software_asset_number'))
                ->label(__('cat/asset_number')),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/example_software_category'))
                ->label(__('cat/category')),
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat/example_software_name'))
                ->label(__('cat/name')),
            ImportColumn::make('sn')
                ->example(__('cat/example_software_sn'))
                ->label(__('cat/sn')),
            ImportColumn::make('specification')
                ->example(__('cat/example_software_specification'))
                ->label(__('cat/specification')),
            ImportColumn::make('max_license_count')
                ->requiredMapping()
                ->rules(['numeric', 'min:0'])
                ->example(0)
                ->label(__('cat/max_license_count')),
            ImportColumn::make('image')
                ->example(__('cat/example_software_image'))
                ->label(__('cat/image')),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat/example_brand'))
                ->label(__('cat/brand')),
            ImportColumn::make('description')
                ->example(__('cat/example_software_description'))
                ->label(__('cat/description')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/import.software_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/import.software_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?Software
    {
        return new Software();
    }
}
