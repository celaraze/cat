<?php

namespace App\Filament\Imports;

use App\Models\PartCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class PartCategoryImporter extends Importer
{
    protected static ?string $model = PartCategory::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('名称')
                ->requiredMapping()
                ->example('示例配件分类'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = '你的配件分类导入已完成并有 '.number_format($import->successful_rows).' 行记录被导入。';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).'行导入失败。';
        }

        return $body;
    }

    public function resolveRecord(): ?PartCategory
    {
        // return Device::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new PartCategory();
    }
}
