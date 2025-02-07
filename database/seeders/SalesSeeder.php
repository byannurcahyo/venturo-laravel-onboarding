<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('t_sales')->insert([
            'id' => '43dbad0d-3e7f-4a0c-be0d-cd4be1c33f5c',
            'm_customer_id' => '42173cf9-bf1b-4b8b-bc40-bb04fd1725a8',
            'date' => '2025-02-06',
        ]);
        DB::table('t_sales_details')->insert([
            'id' => 'd06bae0f-0555-400e-8408-3a065249e973',
            't_sales_id' => '43dbad0d-3e7f-4a0c-be0d-cd4be1c33f5c',
            'm_product_id' => 'ded1829e-211e-475d-adbb-c46ce916dabe',
            'm_product_detail_id' => 'ebffa519-2be8-4e8c-a031-3942bc750d33',
            'total_item' => 1,
            'price' => 10000,
        ]);
    }
}
