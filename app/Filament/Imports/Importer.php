<?php

namespace App\Filament\Imports;

use Vtiful\Kernel\Excel;

abstract class Importer
{
    public array $config;

    public Excel $excel;

    public array $data;

    public function setPath(string $path): void
    {
        $this->config['path'] = $path;
        $this->excel = new Excel($this->config);
    }

    /**
     * 读取Excel文件.
     *
     * @param string $file_name
     * @return Importer
     */
    public function read(string $file_name): static
    {
        $this->data = $this->excel->openFile($file_name)
            ->openSheet()
            ->getSheetData();
        return $this;
    }

    /**
     * 导入接口.
     *
     * @return void
     */
    abstract public function import(): void;
}
