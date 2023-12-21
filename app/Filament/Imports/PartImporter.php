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
                ->example('Example001')
                ->label(__('cat.asset_number')),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example(__('cat.example_part_category'))
                ->label(__('cat.category')),
            ImportColumn::make('sn')
                ->example('AAAAAAA')
                ->label(__('cat.sn')),
            ImportColumn::make('specification')
                ->example('500GB')
                ->label(__('cat.specification')),
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
        $body = '你的配件导入已完成并有 '.number_format($import->successful_rows).' 行记录被导入。';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).'行导入失败。';
        }

        return $body;
    }

    public function resolveRecord(): ?Part
    {
        // return Device::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Part();
    }
}
