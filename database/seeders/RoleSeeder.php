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
            ['name' => 'Kades', 'description' => 'Tanda tangan surat'],
            ['name' => 'Sekdes', 'description' => 'Paraf surat'],
            ['name' => 'Kasi', 'description' => 'Pembuat surat'],
            ['name' => 'Umum Desa', 'description' => 'Menomori, meletakkan TTD, dan barcode'],
        ]);
    }
} 