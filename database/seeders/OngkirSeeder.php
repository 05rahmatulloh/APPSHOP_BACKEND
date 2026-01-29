<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ongkir;

class OngkirSeeder extends Seeder
{
    public function run(): void
    {
        Ongkir::truncate(); // optional: reset data dulu

        Ongkir::insert([
            [
                'kampus' => 'kampus1',
                'biaya'  => 7000,
            ],
            [
                'kampus' => 'kampus2',
                'biaya'  => 10000,
            ],
            [
                'kampus' => 'kampus3',
                'biaya'  => 20000,
            ],
        ]);
    }
}
