<?php

namespace App\Filament\Imports;

use App\Services\Information\BrandService;
use App\Services\Information\SoftwareCategoryService;
use App\Services\Information\SoftwareService;
use Exception;
use Illuminate\Support\Facades\DB;

class SoftwareImporter extends Importer
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
                $software_service = new SoftwareService();
                $asset_number = $value[array_search('资产编号', $headers)];
                $category_name = $value[array_search('分类', $headers)];
                $category_id = SoftwareCategoryService::getModelByName($category_name)->getKey();
                $name = $value[array_search('名称', $headers)];
                $sn = $value[array_search('序列号', $headers)];
                $specification = $value[array_search('规格', $headers)];
                $max_license_count = $value[array_search('授权数量', $headers)];
                $brand_name = $value[array_search('品牌', $headers)];
                $brand_id = BrandService::getModelByName($brand_name)->getKey();
                $data = [
                    'asset_number' => $asset_number,
                    'category_id' => $category_id,
                    'name' => $name,
                    'sn' => $sn,
                    'specification' => $specification,
                    'max_license_count' => $max_license_count,
                    'brand_id' => $brand_id,
                ];
                $software_service->create($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
