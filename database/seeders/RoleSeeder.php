<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::insert([
            ['name' => 'Admin', 'description' => 'Dapat Mengkases Semua fitur'],
            ['name' => 'Menandatangani Surat', 'description' => 'Tanda tangan surat'],
            ['name' => 'Memparaf Surat', 'description' => 'Paraf surat'],
            ['name' => 'Pembuat Surat', 'description' => 'Pembuat surat'],
            ['name' => 'Bagian Umum', 'description' => 'Menomori, meletakkan TTD, dan barcode'],
        ]);
    }
} 