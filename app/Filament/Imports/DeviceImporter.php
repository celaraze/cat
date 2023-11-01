<?php

namespace App\Filament\Imports;

use App\Services\Information\BrandService;
use App\Services\Information\DeviceCategoryService;
use App\Services\Information\DeviceService;
use Exception;
use Illuminate\Support\Facades\DB;

class DeviceImporter extends Importer
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
                $device_service = new DeviceService();
                $asset_number = $value[array_search('资产编号', $headers)];
                $category_name = $value[array_search('分类', $headers)];
                $category_id = DeviceCategoryService::getModelByName($category_name)->getKey();
                $name = $value[array_search('名称', $headers)];
                $sn = $value[array_search('序列号', $headers)];
                $specification = $value[array_search('规格', $headers)];
                $brand_name = $value[array_search('品牌', $headers)];
                $brand_id = BrandService::getModelByName($brand_name)->getKey();
                $data = [
                    'asset_number' => $asset_number,
                    'category_id' => $category_id,
                    'name' => $name,
                    'sn' => $sn,
                    'specification' => $specification,
                    'brand_id' => $brand_id,
                ];
                $device_service->create($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
