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
                ->label('资产编号')
                ->requiredMapping()
                ->rules(['required'])
                ->example('Example001'),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->label('分类')
                ->requiredMapping()
                ->rules(['required'])
                ->example('操作系统'),
            ImportColumn::make('name')
                ->label('名称')
                ->requiredMapping()
                ->example('Windows 10 Pro'),
            ImportColumn::make('sn')
                ->label('序列号')
                ->requiredMapping()
                ->example('AAAAAAA'),
            ImportColumn::make('specification')
                ->label('规格')
                ->requiredMapping()
                ->example('LTSC'),
            ImportColumn::make('image')
                ->label('照片')
                ->requiredMapping()
                ->example('https://test.com/logo.png'),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->label('品牌')
                ->requiredMapping()
                ->rules(['required'])
                ->example('微软 Microsoft'),
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
