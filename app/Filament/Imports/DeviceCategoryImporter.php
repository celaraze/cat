<?php

namespace App\Filament\Imports;

use App\Models\DeviceCategory;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DeviceCategoryImporter extends Importer
{
    protected static ?string $model = DeviceCategory::class;


    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->label('名称')
                ->requiredMapping()
                ->example('示例设备分类'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = '你的设备分类导入已完成并有 ' . number_format($import->successful_rows) . ' 行记录被导入。';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . '行导入失败。';
        }

        return $body;
    }

    public function resolveRecord(): ?DeviceCategory
    {
        // return Device::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new DeviceCategory();
    }
}
