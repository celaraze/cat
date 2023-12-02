<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SoftwareCategoriesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('software_categories')->delete();

        \DB::table('software_categories')->insert([
            0 => [
                'id' => 1,
                'name' => '操作系统',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            1 => [
                'id' => 2,
                'name' => '办公应用',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            2 => [
                'id' => 3,
                'name' => '图像处理',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            3 => [
                'id' => 4,
                'name' => '网络工具',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            4 => [
                'id' => 5,
                'name' => '影音工具',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            5 => [
                'id' => 6,
                'name' => '系统工具',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
            6 => [
                'id' => 7,
                'name' => '设计工具',
                'deleted_at' => null,
                'created_at' => null,
                'updated_at' => null,
            ],
        ]);

    }
}
