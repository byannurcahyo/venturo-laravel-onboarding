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
        DB::table('m_role')->insert([
            'id' => '8d73e100-e164-417b-8420-25ed2f4092c3',
            'name' => 'Admin',
            'access' => json_encode([
                'create' => true,
                'read' => true,
                'update' => true,
                'delete' => true,
            ])
        ]);
        DB::table('m_role')->insert([
            'id' => 'dc492e77-742f-4c58-9479-f270887ffe5c',
            'name' => 'Staff',
            'access' => json_encode([
                'create' => false,
                'read' => true,
                'update' => false,
                'delete' => false,
            ])
        ]);

    }
}
