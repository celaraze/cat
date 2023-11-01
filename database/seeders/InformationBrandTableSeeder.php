<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InformationBrandTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('information_brands')->delete();

        \DB::table('information_brands')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => '微软 Microsoft',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => '英特尔 Intel',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => 'AMD',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            3 =>
                array(
                    'id' => 4,
                    'name' => '苹果 Apple',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            4 =>
                array(
                    'id' => 5,
                    'name' => '英伟达 Nvidia',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            5 =>
                array(
                    'id' => 6,
                    'name' => '微星 MSI',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            6 =>
                array(
                    'id' => 7,
                    'name' => '金士顿 Kingston',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            7 =>
                array(
                    'id' => 8,
                    'name' => '西部数据 WD',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            8 =>
                array(
                    'id' => 9,
                    'name' => '希捷 Seagate',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            9 =>
                array(
                    'id' => 10,
                    'name' => '华硕 ASUS',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            10 =>
                array(
                    'id' => 11,
                    'name' => '联想 Lenovo',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            11 =>
                array(
                    'id' => 12,
                    'name' => '惠普 HP/HPE',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            12 =>
                array(
                    'id' => 13,
                    'name' => '华为 Huawei',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            13 =>
                array(
                    'id' => 14,
                    'name' => '小米 MI',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            14 =>
                array(
                    'id' => 15,
                    'name' => '荣耀 Honor',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            15 =>
                array(
                    'id' => 16,
                    'name' => '七彩虹 Colorful',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            16 =>
                array(
                    'id' => 17,
                    'name' => '影驰 Galaxy',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
        ));


    }
}
