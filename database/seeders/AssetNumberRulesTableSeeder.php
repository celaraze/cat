<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AssetNumberRulesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('asset_number_rules')->delete();

        \DB::table('asset_number_rules')->insert([
            0 => [
                'id' => 3,
                'name' => '设备自动生成',
                'formula' => 'DEVICE-{year}{month}{day}-{auto-increment}',
                'auto_increment_length' => 5,
                'auto_increment_count' => 0,
                'class_name' => 'App\\Models\\Device',
                'is_auto' => 1,
                'deleted_at' => null,
                'created_at' => '2023-10-17 14:42:23',
                'updated_at' => '2023-12-02 14:18:41',
            ],
            1 => [
                'id' => 4,
                'name' => '配件自动生成',
                'formula' => 'PART-{year}{month}{day}-{auto-increment}',
                'auto_increment_length' => 5,
                'auto_increment_count' => 0,
                'class_name' => 'App\\Models\\Part',
                'is_auto' => 1,
                'deleted_at' => null,
                'created_at' => '2023-10-17 14:42:36',
                'updated_at' => '2023-12-02 14:18:53',
            ],
            2 => [
                'id' => 5,
                'name' => '软件自动生成',
                'formula' => 'SOFTWARE-{year}{month}{day}-{auto-increment}',
                'auto_increment_length' => 5,
                'auto_increment_count' => 0,
                'class_name' => 'App\\Models\\Software',
                'is_auto' => 1,
                'deleted_at' => null,
                'created_at' => '2023-12-02 14:18:29',
                'updated_at' => '2023-12-02 14:19:03',
            ],
        ]);

    }
}
