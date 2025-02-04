<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        DB::table('m_user')->insert([
            'id' => $faker->uuid,
            'm_user_roles_id' => '8d73e100-e164-417b-8420-25ed2f4092c3',
            'name' => 'Admin',
            'email' => 'admin@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'updated_security' => now()
        ]);
    }
}
