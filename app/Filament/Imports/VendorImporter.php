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
                ->example(__('cat/vendor.importer.name_example'))
                ->label(__('cat/vendor.name')),
            ImportColumn::make('address')
                ->requiredMapping()
                ->example(__('cat/vendor.importer.address_example'))
                ->label(__('cat/vendor.address')),
            ImportColumn::make('public_phone_number')
                ->example(__('cat/user.importer.public_phone_number_example'))
                ->label(__('cat/vendor.public_phone_number')),
            ImportColumn::make('referrer')
                ->example(__('cat/user.importer.referrer_example'))
                ->label(__('cat/user.referrer')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __('cat/user.importer.import_success', ['success_count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__('cat/user.importer.import_failure', ['failure_count' => number_format($failedRowsCount)]);
        }

        return $body;
    }

    public function resolveRecord(): ?Vendor
    {
        return new Vendor();
    }
}
