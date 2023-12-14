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
                ->label('资产编号'),
            ImportColumn::make('category')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example('操作系统')
                ->label('分类'),
            ImportColumn::make('name')
                ->requiredMapping()
                ->example('Windows 10 Pro')
                ->label('名称'),
            ImportColumn::make('sn')
                ->example('AAAAAAA')
                ->label('序列号'),
            ImportColumn::make('specification')
                ->example('LTSC')
                ->label('规格'),
            ImportColumn::make('max_license_count')
                ->requiredMapping()
                ->rules(['numeric', 'min:0'])
                ->example('LTSC')
                ->label('授权数量'),
            ImportColumn::make('image')
                ->example('https://test.com/logo.png')
                ->label('照片'),
            ImportColumn::make('brand')
                ->relationship(resolveUsing: 'name')
                ->requiredMapping()
                ->example('微软 Microsoft')
                ->label('品牌'),
            ImportColumn::make('description')
                ->example('这是一个操作系统软件')
                ->label('说明'),
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
