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
                ->label('资产编号'),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example('硬盘')
                ->label('分类'),
            ImportColumn::make('sn')
                ->example('AAAAAAA')
                ->label('序列号'),
            ImportColumn::make('specification')
                ->example('500GB')
                ->label('规格'),
            ImportColumn::make('image')
                ->example('https://test.com/logo.png')
                ->label('照片'),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example('微软 Microsoft')
                ->label('品牌'),
            ImportColumn::make('description')
                ->example('这是一个 CPU')
                ->label('说明'),
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
