<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_product_detail')->insert([
            'id' => '7ae36a7e-396c-4771-a4f9-b63544a6184a',
            'm_product_id' => 'd323521a-d261-4e25-a643-9ccb0d7e866a',
            'type' => 'Topping',
            'description' => 'Oreo',
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '14a58d5d-6898-4912-8bc8-52f63c356f41',
            'm_product_id' => 'e73b4670-858b-40f0-9d9e-b65b7f4bc89a',
            'type' => 'Topping',
            'description' => 'Cream',
        ]);
        DB::table('m_product_detail')->insert([
            'id' => 'ebffa519-2be8-4e8c-a031-3942bc750d33',
            'm_product_id' => 'ded1829e-211e-475d-adbb-c46ce916dabe',
            'type' => 'Level',
            'description' => 'Level 1',
        ]);
    }
}
