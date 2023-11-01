<?php

namespace App\Filament\Imports;

use App\Services\Information\BrandService;
use App\Services\Information\PartCategoryService;
use App\Services\Information\PartService;
use Exception;
use Illuminate\Support\Facades\DB;

class PartImporter extends Importer
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
                $part_service = new PartService();
                $asset_number = $value[array_search('资产编号', $headers)];
                $category_name = $value[array_search('分类', $headers)];
                $category_id = PartCategoryService::getModelByName($category_name)->getKey();
                $sn = $value[array_search('序列号', $headers)];
                $specification = $value[array_search('规格', $headers)];
                $brand_name = $value[array_search('品牌', $headers)];
                $brand_id = BrandService::getModelByName($brand_name)->getKey();
                $data = [
                    'asset_number' => $asset_number,
                    'category_id' => $category_id,
                    'sn' => $sn,
                    'specification' => $specification,
                    'brand_id' => $brand_id,
                ];
                $part_service->create($data);
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
