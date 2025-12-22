<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FeeCategory;

class FeeCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Medical Operations',
                'code' => 'MED_OP',
                'description' => 'Surgical and medical procedures',
                'is_active' => true,
            ],
            [
                'name' => 'Medicines',
                'code' => 'MED',
                'description' => 'Pharmaceutical products and drugs',
                'is_active' => true,
            ],
            [
                'name' => 'Laboratory Tests',
                'code' => 'LAB',
                'description' => 'Medical laboratory tests and diagnostics',
                'is_active' => true,
            ],
            [
                'name' => 'Consultations',
                'code' => 'CONSULT',
                'description' => 'Doctor consultations and check-ups',
                'is_active' => true,
            ],
            [
                'name' => 'Room Charges',
                'code' => 'ROOM',
                'description' => 'Hospital room and bed charges',
                'is_active' => true,
            ],
            [
                'name' => 'Emergency Services',
                'code' => 'EMERG',
                'description' => 'Emergency and critical care services',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            FeeCategory::create($category);
        }
    }
}
