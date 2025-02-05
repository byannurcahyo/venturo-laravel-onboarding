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
        DB::table('m_users')->insert([
            'id' => '2a58a7ad-d64a-3138-a626-ec6a04ad467b',
            'm_user_roles_id' => '8d73e100-e164-417b-8420-25ed2f4092c3',
            'name' => 'Admin Landa',
            'email' => 'admin@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'updated_security' => now()
        ]);
        DB::table('m_users')->insert([
            'id' => '49bce461-1ca3-3475-9e32-a728e879abb2',
            'm_user_roles_id' => 'dc492e77-742f-4c58-9479-f270887ffe5c',
            'name' => 'Staff Landa',
            'email' => 'staff@landa.co.id',
            'password' => Hash::make('devGanteng'),
            'updated_security' => now()
        ]);
    }
}
