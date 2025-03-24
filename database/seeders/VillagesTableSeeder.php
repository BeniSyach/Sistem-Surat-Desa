<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VillagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('villages')->insert([
            [
                'name' => 'Desa Pergulaan',
                'address' => 'Kecamatan Sei Rampah, Kabupaten Serdang Bedagai',
                'phone' => null,
                'email' => null,
                'village_head' => null,
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Firdaus',
                'address' => null,
                'phone' => null,
                'email' => null,
                'village_head' => null,
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Pematang Ganjang',
                'address' => null,
                'phone' => null,
                'email' => null,
                'village_head' => null,
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cempedak Lobang',
                'address' => null,
                'phone' => null,
                'email' => null,
                'village_head' => null,
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Sei Rampah',
                'address' => null,
                'phone' => null,
                'email' => null,
                'village_head' => null,
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Simpang 4',
                'address' => null,
                'phone' => null,
                'email' => null,
                'village_head' => null,
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
