<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_product_category')->insert([
            'id' => '9707d930-8cea-4ff8-b250-59a58af2f4b2',
            'name' => 'Main Course',
        ]);
        DB::table('m_product_category')->insert([
            'id' => 'c5b95d69-4f49-4531-9f98-8236572c27bb',
            'name' => 'Beverage',
        ]);
        DB::table('m_product_category')->insert([
            'id' => 'e1325fc2-6e3f-486c-a0d9-b2fa81d29342',
            'name' => 'Snack',
        ]);
        DB::table('m_product_category')->insert([
            'id' => '5fed4245-4bd2-4d08-a313-8c40180003c3',
            'name' => 'Add On',
        ]);
    }
}
