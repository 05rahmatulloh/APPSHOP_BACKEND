<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Elektronik',
                'type' => 'sale',
            ],
            [
                'name' => 'Penyewaan',
                'type' => 'rent',
            ],
            [
                'name' => 'Makanan',
                'type' => 'sale',
            ],
        ];

        foreach ($categories as $cat) {
            DB::table('categories')->insert([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'type' => $cat['type'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
