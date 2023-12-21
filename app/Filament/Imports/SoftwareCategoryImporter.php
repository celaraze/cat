<?php

namespace App\Filament\Imports;

use App\Models\SoftwareCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class SoftwareCategoryImporter extends Importer
{
    protected static ?string $model = SoftwareCategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->example(__('cat.example_software_category'))
                ->label(__('cat.name')),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = '你的软件分类导入已完成并有 '.number_format($import->successful_rows).' 行记录被导入。';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).'行导入失败。';
        }

        return $body;
    }

    public function resolveRecord(): ?SoftwareCategory
    {
        // return Device::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new SoftwareCategory();
    }
}
