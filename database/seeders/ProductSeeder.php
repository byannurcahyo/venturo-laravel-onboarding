<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_product')->insert([
            'id' => 'd323521a-d261-4e25-a643-9ccb0d7e866a',
            'm_product_category_id' => '9707d930-8cea-4ff8-b250-59a58af2f4b2',
            'name' => 'Ice Cream',
            'price' => 10000,
            'description' => 'Delicious ice cream',
            'is_available' => 1,
        ]);
        DB::table('m_product')->insert([
            'id' => 'e73b4670-858b-40f0-9d9e-b65b7f4bc89a',
            'm_product_category_id' => 'c5b95d69-4f49-4531-9f98-8236572c27bb',
            'name' => 'Milkshake',
            'price' => 15000,
            'description' => 'Delicious milkshake',
            'is_available' => 1,
        ]);
        DB::table('m_product')->insert([
            'id' => 'ded1829e-211e-475d-adbb-c46ce916dabe',
            'm_product_category_id' => 'e1325fc2-6e3f-486c-a0d9-b2fa81d29342',
            'name' => 'Chips',
            'price' => 5000,
            'description' => 'Delicious chips',
            'is_available' => 1,
        ]);
    }
}
