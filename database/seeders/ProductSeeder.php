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
            'name' => 'Chicken Steak',
            'price' => 30000,
            'description' => 'Fried chicken steak flour is served with local potatoes, mix vegetable and brown sauce.',
            'is_available' => 1,
        ]);
        DB::table('m_product')->insert([
            'id' => 'df98f1de-affb-46c3-9e40-b9d273007c10',
            'm_product_category_id' => '9707d930-8cea-4ff8-b250-59a58af2f4b2',
            'name' => 'Sirloin Steak',
            'price' => 30000,
            'description' => 'The outer meat steak is fried flour served with local potatoes, mix vegetables and brown sauce.',
            'is_available' => 1,
        ]);
        DB::table('m_product')->insert([
            'id' => 'abbb490e-9709-4680-a241-867fd6b9faf9',
            'm_product_category_id' => '9707d930-8cea-4ff8-b250-59a58af2f4b2',
            'name' => 'Tenderloin Steak',
            'price' => 30000,
            'description' => 'Steak meat parts have in fried flour served with local potatoes, mix vegetable and brown sauce.',
            'is_available' => 1,
        ]);
        DB::table('m_product')->insert([
            'id' => 'e73b4670-858b-40f0-9d9e-b65b7f4bc89a',
            'm_product_category_id' => 'c5b95d69-4f49-4531-9f98-8236572c27bb',
            'name' => 'Milkshake',
            'price' => 15000,
            'description' => 'A creamy and refreshing milkshake made with the finest ingredients, perfect for a delightful treat.',
            'is_available' => 1,
        ]);
        DB::table('m_product')->insert([
            'id' => 'ded1829e-211e-475d-adbb-c46ce916dabe',
            'm_product_category_id' => 'e1325fc2-6e3f-486c-a0d9-b2fa81d29342',
            'name' => 'French Fries',
            'price' => 20000,
            'description' => 'French fries are served with a sprinkling of salt and a choice of sauce, perfect for a snack.',
            'is_available' => 1,
        ]);
        DB::table('m_product')->insert([
            'id' => 'bc8213b0-dffb-454a-a34f-5daefa7ad223',
            'm_product_category_id' => '5fed4245-4bd2-4d08-a313-8c40180003c3',
            'name' => 'Sauce',
            'price' => 5000,
            'description' => 'A variety of sauces that can be selected according to taste, perfect for adding flavor to the main menu.',
            'is_available' => 1,
        ]);
    }
}
