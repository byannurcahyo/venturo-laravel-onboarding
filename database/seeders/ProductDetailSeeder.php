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
            'type' => 'Variant',
            'description' => 'Regular',
            'price' => 30000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '84801817-0f74-4c48-b436-95b2347aaafd',
            'm_product_id' => 'd323521a-d261-4e25-a643-9ccb0d7e866a',
            'type' => 'Variant',
            'description' => 'Double',
            'price' => 30000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '9755a54d-95fb-4ef6-b365-c74505fe9694',
            'm_product_id' => 'df98f1de-affb-46c3-9e40-b9d273007c10',
            'type' => 'Variant',
            'description' => 'Regular',
            'price' => 30000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '1b8a73cb-3250-40e8-8d9f-34873600ec5d',
            'm_product_id' => 'df98f1de-affb-46c3-9e40-b9d273007c10',
            'type' => 'Variant',
            'description' => 'Double',
            'price' => 30000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '3290c128-31fe-4983-80fb-05a41fc3876d',
            'm_product_id' => 'abbb490e-9709-4680-a241-867fd6b9faf9',
            'type' => 'Variant',
            'description' => 'Regular',
            'price' => 30000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => 'eadbac07-1ae3-4cf2-8040-4c3c1ef2a5f1',
            'm_product_id' => 'abbb490e-9709-4680-a241-867fd6b9faf9',
            'type' => 'Variant',
            'description' => 'Double',
            'price' => 30000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '59f287e1-b875-4a4f-bf28-0c1d5a997a71',
            'm_product_id' => 'e73b4670-858b-40f0-9d9e-b65b7f4bc89a',
            'type' => 'Variant',
            'description' => 'Chocolate',
            'price' => 15000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '31ee3106-8c57-464c-b136-42a2725507d4',
            'm_product_id' => 'e73b4670-858b-40f0-9d9e-b65b7f4bc89a',
            'type' => 'Variant',
            'description' => 'Strawberry',
            'price' => 15000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '0369320e-8415-4591-9b49-dd7ce076cd57',
            'm_product_id' => 'e73b4670-858b-40f0-9d9e-b65b7f4bc89a',
            'type' => 'Variant',
            'description' => 'Vanilla',
            'price' => 15000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '72c6517b-4832-422d-8c42-91868d8e599d',
            'm_product_id' => 'ded1829e-211e-475d-adbb-c46ce916dabe',
            'type' => 'Topping',
            'description' => 'Cheese',
            'price' => 20000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '469a3553-4ff3-414a-8a60-9900f994d2bf',
            'm_product_id' => 'ded1829e-211e-475d-adbb-c46ce916dabe',
            'type' => 'Topping',
            'description' => 'Mayonnaise',
            'price' => 20000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => '9fc7eccd-8c27-4a91-b3f4-d537ec67f864',
            'm_product_id' => 'bc8213b0-dffb-454a-a34f-5daefa7ad223',
            'type' => 'Variant',
            'description' => 'Cheese Sauce',
            'price' => 20000,
        ]);
        DB::table('m_product_detail')->insert([
            'id' => 'a5a59fe5-4956-44aa-9dad-c4e16d0fb30c',
            'm_product_id' => 'bc8213b0-dffb-454a-a34f-5daefa7ad223',
            'type' => 'Variant',
            'description' => 'BBQ Sauce',
            'price' => 5000,
        ]);
    }
}
