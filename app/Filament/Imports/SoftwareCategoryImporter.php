<?php

namespace App\Filament\Imports;

use App\Services\Information\SoftwareCategoryService;
use Exception;
use Illuminate\Support\Facades\DB;

class SoftwareCategoryImporter extends Importer
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
                $software_category_service = new SoftwareCategoryService();
                $name = $value[array_search('名称', $headers)];
                $data = [
                    'name' => $name,
                ];
                $software_category_service->create($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
