<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all roles
        $roles = DB::table('roles')->whereIn('name', [
            'Menandatangani Surat',
            'Memparaf Surat',
            'Pembuat Surat',
            'Bagian Umum'
        ])->get()->keyBy('name');

        // Get village IDs
        $villages = DB::table('villages')->get();
        
        // Mapping of village names to staff data
        $villageStaffData = [
            'Desa Pergulaan' => [
                'Menandatangani Surat' => 'Ari Wirawan',
                'Memparaf Surat' => 'Sekdes Pergulaan',
                'Pembuat Surat' => 'Kasi Pergulaan',
                'Bagian Umum' => 'Umum Pergulaan'
            ],
            'Desa Firdaus' => [
                'Menandatangani Surat' => 'Suherwin',
                'Memparaf Surat' => 'Sekdes Firdaus',
                'Pembuat Surat' => 'Kasi Firdaus',
                'Bagian Umum' => 'Umum Firdaus'
            ],
            'Desa Pematang Ganjang' => [
                'Menandatangani Surat' => 'Sugiono',
                'Memparaf Surat' => 'Sekdes Pematang Ganjang',
                'Pembuat Surat' => 'Kasi Pematang Ganjang',
                'Bagian Umum' => 'Umum Pematang Ganjang'
            ],
            'Cempedak Lobang' => [
                'Menandatangani Surat' => 'Ahmadi Darma',
                'Memparaf Surat' => 'Sekdes Cempedak Lobang',
                'Pembuat Surat' => 'Kasi Cempedak Lobang',
                'Bagian Umum' => 'Umum Cempedak Lobang'
            ],
            'Desa Sei Rampah' => [
                'Menandatangani Surat' => 'Cipto',
                'Memparaf Surat' => 'Sekdes Sei Rampah',
                'Pembuat Surat' => 'Kasi Sei Rampah',
                'Bagian Umum' => 'Umum Sei Rampah'
            ],
            'Desa Simpang 4' => [
                'Menandatangani Surat' => 'Nasaruddin',
                'Memparaf Surat' => 'Sekdes Simpang 4',
                'Pembuat Surat' => 'Kasi Simpang 4',
                'Bagian Umum' => 'Umum Simpang 4'
            ],
        ];

        // Create users for each village and role
        foreach ($villages as $village) {
            if (isset($villageStaffData[$village->name])) {
                foreach ($villageStaffData[$village->name] as $roleName => $staffName) {
                    if (isset($roles[$roleName])) {
                        // Generate email based on role and village
                        $roleSlug = strtolower(str_replace(' ', '', $roleName));
                        $villageSlug = strtolower(str_replace(' ', '-', $village->name));
                        $email = $roleSlug . '@' . $villageSlug . '.gmail.com';
                        
                        DB::table('users')->insert([
                            'name' => $staffName,
                            'email' => $email,
                            'password' => Hash::make('masuk123'),
                            'role_id' => $roles[$roleName]->id,
                            'village_id' => $village->id,
                            'is_active' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
    }
} 