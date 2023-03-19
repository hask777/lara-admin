<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'disabled',
            ],
            [
                'name' => 'user',
            ],
            [
                'name' => 'admin',
            ],
        ];
        DB::table('roles')->insert($data);
    }
}
