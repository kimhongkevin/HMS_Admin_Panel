<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => 'Cardiology', 'code' => 'CARD', 'description' => 'Heart and vascular care'],
            ['name' => 'Pediatrics', 'code' => 'PEDI', 'description' => 'Child and adolescent care'],
            ['name' => 'Neurology', 'code' => 'NEUR', 'description' => 'Brain and nervous system'],
            ['name' => 'Emergency', 'code' => 'ER', 'description' => 'Critical and emergency care'],
            ['name' => 'Laboratory', 'code' => 'LAB', 'description' => 'Diagnostic testing and analysis'],
            ['name' => 'Pharmacy', 'code' => 'PHAR', 'description' => 'Medication and prescriptions'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept + ['is_active' => true]);
        }
    }
}
