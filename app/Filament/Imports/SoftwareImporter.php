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
                ->example('Example001')
                ->label(__('cat.asset_number')),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat.example_software_category'))
                ->label(__('cat.category')),
            ImportColumn::make('name')
                ->requiredMapping()
                ->example('Windows 10 Pro')
                ->label(__('cat.name')),
            ImportColumn::make('sn')
                ->example('AAAAAAA')
                ->label(__('cat.sn')),
            ImportColumn::make('specification')
                ->example('LTSC')
                ->label(__('cat.specification')),
            ImportColumn::make('max_license_count')
                ->requiredMapping()
                ->rules(['numeric', 'min:0'])
                ->example('LTSC')
                ->label(__('cat.max_license_count')),
            ImportColumn::make('image')
                ->example('https://test.com/logo.png')
                ->label(__('cat.image')),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat.example_brand'))
                ->label(__('cat.brand')),
            ImportColumn::make('description')
                ->example(__('cat.example_description'))
                ->label(__('cat.description')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = '你的软件导入已完成并有 '.number_format($import->successful_rows).' 行记录被导入。';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).'行导入失败。';
        }

        return $body;
    }

    public function resolveRecord(): ?Software
    {
        // return Device::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Software();
    }
}
