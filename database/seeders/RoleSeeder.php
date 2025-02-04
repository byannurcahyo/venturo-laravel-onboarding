<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        DB::table('m_user_roles')->insert([
            'id' => $faker->uuid,
            'name' => 'Admin',
            'access' => '["admin"]'
        ]);
        DB::table('m_user_roles')->insert([
            'id' => $faker->uuid,
            'name' => 'Staff',
            'access' => '["staff"]'
        ]);

    }
}
