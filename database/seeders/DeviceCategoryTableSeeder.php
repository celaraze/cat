<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DeviceCategoryTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('device_categories')->delete();

        \DB::table('device_categories')->insert([
            0 => [
                'id' => 1,
                'name' => '台式机',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            1 => [
                'id' => 2,
                'name' => '笔记本',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            2 => [
                'id' => 3,
                'name' => '服务器',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            3 => [
                'id' => 4,
                'name' => '交换机',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            4 => [
                'id' => 5,
                'name' => '显示器',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            5 => [
                'id' => 6,
                'name' => '路由器',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            6 => [
                'id' => 7,
                'name' => '打印机',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            7 => [
                'id' => 8,
                'name' => '扫描仪',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            8 => [
                'id' => 9,
                'name' => '复印机',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            9 => [
                'id' => 10,
                'name' => '平板电脑',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            10 => [
                'id' => 11,
                'name' => 'PDA',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);

    }
}
