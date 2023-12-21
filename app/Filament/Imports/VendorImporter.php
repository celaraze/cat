<?php

namespace App\Filament\Imports;

use App\Models\Vendor;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class VendorImporter extends Importer
{
    protected static ?string $model = Vendor::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat.example_vendor_name'))
                ->label(__('cat.name')),
            ImportColumn::make('address')
                ->requiredMapping()
                ->example(__('cat.example_vendor_address'))
                ->label(__('cat.address')),
            ImportColumn::make('public_phone_number')
                ->example(__('cat.example_vendor_public_phone_number'))
                ->label(__('cat.public_phone_number')),
            ImportColumn::make('referrer')
                ->example(__('cat.example_vendor_referrer'))
                ->label(__('cat.referrer')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat.import.vendor_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat.import.vendor_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?Vendor
    {
        return new Vendor();
    }
}
