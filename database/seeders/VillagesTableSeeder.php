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
                'name' => 'Kecamatan Sei Rampah',
                'address' => 'Kecamatan Sei Rampah, Kabupaten Serdang Bedagai',
                'phone' => '0',
                'email' => 'desa.pergulaan@gmail.com',
                'village_head' => '-',
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Pergulaan',
                'address' => 'Kecamatan Sei Rampah, Kabupaten Serdang Bedagai',
                'phone' => '0',
                'email' => 'desa.pergulaan@gmail.com',
                'village_head' => 'Ari Wirawan',
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Firdaus',
                'address' => 'Kecamatan Sei Rampah, Kabupaten Serdang Bedagai',
                'phone' => '0',
                'email' => 'desa.firdaus@gmail.com',
                'village_head' => 'Suherwin',
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Pematang Ganjang',
                'address' => 'Kecamatan Sei Rampah, Kabupaten Serdang Bedagai',
                'phone' => '0',
                'email' => 'desa.pematangganjang@gmail.com',
                'village_head' => 'Sugiono',
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cempedak Lobang',
                'address' => 'Kecamatan Sei Rampah, Kabupaten Serdang Bedagai',
                'phone' => '0',
                'email' => 'desa.cempedaklobang@gmail.com',
                'village_head' => 'Ahmadi Darma',
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Sei Rampah',
                'address' => 'Kecamatan Sei Rampah, Kabupaten Serdang Bedagai',
                'phone' => '0',
                'email' => 'desa.seirampah@gmail.com',
                'village_head' => 'Cipto',
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Desa Simpang 4',
                'address' => 'Kecamatan Sei Rampah, Kabupaten Serdang Bedagai',
                'phone' => '0',
                'email' => 'desa.simpang4@gmail.com',
                'village_head' => 'Nasaruddin',
                'logo' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
