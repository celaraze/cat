<?php

namespace App\Filament\Imports;

use App\Services\Information\DeviceCategoryService;
use Exception;
use Illuminate\Support\Facades\DB;

class DeviceCategoryImporter extends Importer
{

    /**
     * 导入.
     *
     * @throws Exception
     */
    public function import(): void
    {
        try {
            DB::beginTransaction();
            $headers = $this->data[0];
            unset($this->data[0]);
            $this->data = array_values($this->data);
            foreach ($this->data as $value) {
                $device_category_service = new DeviceCategoryService();
                $name = $value[array_search('名称', $headers)];
                $data = [
                    'name' => $name,
                ];
                $device_category_service->create($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
