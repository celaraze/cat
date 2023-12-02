<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        \DB::table('roles')->delete();

        \DB::table('roles')->insert([
            0 => [
                'id' => 1,
                'name' => '超级管理员',
                'guard_name' => 'web',
                'created_at' => '2023-12-02 14:04:28',
                'updated_at' => '2023-12-02 14:09:10',
            ],
        ]);

    }
}
