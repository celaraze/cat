<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class InformationDeviceCategoryTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('information_device_categories')->delete();

        \DB::table('information_device_categories')->insert(array(
            0 =>
                array(
                    'id' => 1,
                    'name' => '台式机',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            1 =>
                array(
                    'id' => 2,
                    'name' => '笔记本',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            2 =>
                array(
                    'id' => 3,
                    'name' => '服务器',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            3 =>
                array(
                    'id' => 4,
                    'name' => '交换机',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            4 =>
                array(
                    'id' => 5,
                    'name' => '显示器',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            5 =>
                array(
                    'id' => 6,
                    'name' => '路由器',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            6 =>
                array(
                    'id' => 7,
                    'name' => '打印机',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            7 =>
                array(
                    'id' => 8,
                    'name' => '扫描仪',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            8 =>
                array(
                    'id' => 9,
                    'name' => '复印机',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            9 =>
                array(
                    'id' => 10,
                    'name' => '平板电脑',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            10 =>
                array(
                    'id' => 11,
                    'name' => 'PDA',
                    'deleted_at' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
        ));


    }
}
