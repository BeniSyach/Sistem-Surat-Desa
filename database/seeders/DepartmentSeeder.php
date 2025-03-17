<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Village;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Pemerintahan', 'description' => 'Bidang Pemerintahan Desa'],
            ['name' => 'Kesejahteraan', 'description' => 'Bidang Kesejahteraan Masyarakat'],
            ['name' => 'Pelayanan', 'description' => 'Bidang Pelayanan Masyarakat'],
            ['name' => 'Pembangunan', 'description' => 'Bidang Pembangunan Desa'],
        ];

        $villages = Village::all();

        foreach ($villages as $village) {
            foreach ($departments as $department) {
                Department::create([
                    'name' => $department['name'],
                    'description' => $department['description'],
                    'village_id' => $village->id,
                ]);
            }
        }
    }
} 