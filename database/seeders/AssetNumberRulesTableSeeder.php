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

        \DB::table('asset_number_rules')->insert(array (
            0 =>
            array (
                'id' => 3,
                'name' => '设备自动生成',
                'formula' => 'DEVICE-{year}{month}{day}-{auto-increment}',
                'auto_increment_length' => 5,
                'auto_increment_count' => 0,
                'class_name' => 'device',
                'is_auto' => 1,
                'deleted_at' => NULL,
                'created_at' => '2023-10-17 14:42:23',
                'updated_at' => '2023-10-17 14:44:24',
            ),
            1 =>
            array (
                'id' => 4,
                'name' => '配件自动生成',
                'formula' => 'PART-{year}{month}{day}-{auto-increment}',
                'auto_increment_length' => 5,
                'auto_increment_count' => 0,
                'class_name' => 'part',
                'is_auto' => 1,
                'deleted_at' => NULL,
                'created_at' => '2023-10-17 14:42:36',
                'updated_at' => '2023-10-17 14:44:14',
            ),
        ));


    }
}
