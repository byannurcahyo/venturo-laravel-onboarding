<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_customers')->insert([
            'id' => '42173cf9-bf1b-4b8b-bc40-bb04fd1725a8',
            'm_user_id' => '2a58a7ad-d64a-3138-a626-ec6a04ad467b',
            'name' => 'Admin',
            'address' => 'Jl. Jambu No. 1',
            'phone' => '081234567890',
        ]);
        DB::table('m_customers')->insert([
            'id' => 'f3b3b3b3-1b3b-4b3b-b3b3-b3b3b3b3b3b3',
            'm_user_id' => '49bce461-1ca3-3475-9e32-a728e879abb2',
            'name' => 'Staff',
            'address' => 'Jl. Mangga No. 1',
            'phone' => '081234567891',
        ]);
    }
}
