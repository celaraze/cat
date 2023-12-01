<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class BrandTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('brands')->delete();

        \DB::table('brands')->insert([
            0 => [
                'id' => 1,
                'name' => '微软 Microsoft',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            1 => [
                'id' => 2,
                'name' => '英特尔 Intel',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            2 => [
                'id' => 3,
                'name' => 'AMD',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            3 => [
                'id' => 4,
                'name' => '苹果 Apple',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            4 => [
                'id' => 5,
                'name' => '英伟达 Nvidia',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            5 => [
                'id' => 6,
                'name' => '微星 MSI',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            6 => [
                'id' => 7,
                'name' => '金士顿 Kingston',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            7 => [
                'id' => 8,
                'name' => '西部数据 WD',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            8 => [
                'id' => 9,
                'name' => '希捷 Seagate',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            9 => [
                'id' => 10,
                'name' => '华硕 ASUS',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            10 => [
                'id' => 11,
                'name' => '联想 Lenovo',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            11 => [
                'id' => 12,
                'name' => '惠普 HP/HPE',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            12 => [
                'id' => 13,
                'name' => '华为 Huawei',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            13 => [
                'id' => 14,
                'name' => '小米 MI',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            14 => [
                'id' => 15,
                'name' => '荣耀 Honor',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            15 => [
                'id' => 16,
                'name' => '七彩虹 Colorful',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            16 => [
                'id' => 17,
                'name' => '影驰 Galaxy',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);

    }
}
