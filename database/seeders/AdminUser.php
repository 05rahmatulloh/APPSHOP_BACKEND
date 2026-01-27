<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;

class AdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         User::create([
         'name' => 'Admin Sistem',
         'nim' => '00000001',
         'email' => 'admin@admin.com',
         'password' => 'password', // otomatis di-hash (casts)
         'role' => 'admin',
         'study_program' => 'Teknik Informatika',
         'kampus' => '1',
         'mabna' => 'Mabna A',
         'room_number' => '101',
         'whatsapp' => '081234567890',
         'token' => null,
         ]);

         // User biasa
         User::create([
         'name' => 'rahmat',
         'nim' => '22123456',
         'email' => 'rahmatullohuin@gmail.com',
         'password' => 'password',
         'role' => 'customer',
         'study_program' => 'Sistem Informasi',
         'kampus' => "1",
         'mabna' => 'Mabna B',
         'room_number' => '202',
         'whatsapp' => '089876543210',
         'token' => null,
         ]);
    }
}
