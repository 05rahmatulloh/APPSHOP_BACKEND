<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('products')->insert([
            [
                'category_id' => 1,
                'name' => 'Laptop Asus',
                'price' => 12000000,
'stock' => 10,
 'description' => 'Laptop untuk kerja dan kuliah',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 1,
                'name' => 'Smartphone Samsung',
                'price' => 5000000,
                'stock' => true,
                'description' => 'HP Android terbaru',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category_id' => 2,
                'name' => 'Kaos Polos',
                'price' => 75000,
                'stock' => 10,
                'description' => 'Kaos cotton nyaman dipakai',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
                
            ],
            [
                'category_id' => 3,
                'name' => 'Snack Kentang',
                'price' => 15000,
                'stock' => 80,
                'description' => 'Snack ringan',
                'image' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
