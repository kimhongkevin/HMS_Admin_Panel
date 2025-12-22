<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Fee;
use App\Models\FeeCategory;

class FeeSeeder extends Seeder
{
    public function run(): void
    {
        $categories = FeeCategory::all()->keyBy('code');

        $fees = [
            // Medical Operations
            [
                'fee_category_id' => $categories['MED_OP']->id,
                'name' => 'Heart Surgery',
                'code' => 'OP-HS-001',
                'description' => 'Complete heart surgery procedure',
                'amount' => 5000.00,
                'unit' => 'per procedure',
                'is_taxable' => true,
                'tax_percentage' => 10.00,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['MED_OP']->id,
                'name' => 'Appendectomy',
                'code' => 'OP-AP-002',
                'description' => 'Appendix removal surgery',
                'amount' => 2500.00,
                'unit' => 'per procedure',
                'is_taxable' => true,
                'tax_percentage' => 10.00,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['MED_OP']->id,
                'name' => 'Knee Replacement',
                'code' => 'OP-KR-003',
                'description' => 'Total knee replacement surgery',
                'amount' => 8000.00,
                'unit' => 'per procedure',
                'is_taxable' => true,
                'tax_percentage' => 10.00,
                'is_active' => true,
            ],

            // Medicines
            [
                'fee_category_id' => $categories['MED']->id,
                'name' => 'Paracetamol 500mg',
                'code' => 'MED-PAR-001',
                'description' => 'Pain relief and fever reducer',
                'amount' => 0.50,
                'unit' => 'per tablet',
                'is_taxable' => false,
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['MED']->id,
                'name' => 'Amoxicillin 500mg',
                'code' => 'MED-AMO-002',
                'description' => 'Antibiotic',
                'amount' => 1.50,
                'unit' => 'per capsule',
                'is_taxable' => false,
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['MED']->id,
                'name' => 'Insulin Injection',
                'code' => 'MED-INS-003',
                'description' => 'Diabetes medication',
                'amount' => 25.00,
                'unit' => 'per dose',
                'is_taxable' => false,
                'tax_percentage' => 0,
                'is_active' => true,
            ],

            // Laboratory Tests
            [
                'fee_category_id' => $categories['LAB']->id,
                'name' => 'Complete Blood Count (CBC)',
                'code' => 'LAB-CBC-001',
                'description' => 'Full blood analysis',
                'amount' => 50.00,
                'unit' => 'per test',
                'is_taxable' => true,
                'tax_percentage' => 5.00,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['LAB']->id,
                'name' => 'X-Ray',
                'code' => 'LAB-XR-002',
                'description' => 'X-Ray imaging',
                'amount' => 75.00,
                'unit' => 'per image',
                'is_taxable' => true,
                'tax_percentage' => 5.00,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['LAB']->id,
                'name' => 'MRI Scan',
                'code' => 'LAB-MRI-003',
                'description' => 'Magnetic Resonance Imaging',
                'amount' => 500.00,
                'unit' => 'per scan',
                'is_taxable' => true,
                'tax_percentage' => 5.00,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['LAB']->id,
                'name' => 'CT Scan',
                'code' => 'LAB-CT-004',
                'description' => 'Computed Tomography scan',
                'amount' => 400.00,
                'unit' => 'per scan',
                'is_taxable' => true,
                'tax_percentage' => 5.00,
                'is_active' => true,
            ],

            // Consultations
            [
                'fee_category_id' => $categories['CONSULT']->id,
                'name' => 'General Consultation',
                'code' => 'CON-GEN-001',
                'description' => 'General doctor consultation',
                'amount' => 100.00,
                'unit' => 'per consultation',
                'is_taxable' => true,
                'tax_percentage' => 10.00,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['CONSULT']->id,
                'name' => 'Specialist Consultation',
                'code' => 'CON-SPEC-002',
                'description' => 'Specialist doctor consultation',
                'amount' => 200.00,
                'unit' => 'per consultation',
                'is_taxable' => true,
                'tax_percentage' => 10.00,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['CONSULT']->id,
                'name' => 'Emergency Consultation',
                'code' => 'CON-EMER-003',
                'description' => 'Emergency doctor consultation',
                'amount' => 300.00,
                'unit' => 'per consultation',
                'is_taxable' => true,
                'tax_percentage' => 10.00,
                'is_active' => true,
            ],

            // Room Charges
            [
                'fee_category_id' => $categories['ROOM']->id,
                'name' => 'General Ward',
                'code' => 'ROOM-GW-001',
                'description' => 'General ward bed',
                'amount' => 50.00,
                'unit' => 'per day',
                'is_taxable' => false,
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['ROOM']->id,
                'name' => 'Private Room',
                'code' => 'ROOM-PR-002',
                'description' => 'Private room with AC',
                'amount' => 150.00,
                'unit' => 'per day',
                'is_taxable' => false,
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['ROOM']->id,
                'name' => 'ICU',
                'code' => 'ROOM-ICU-003',
                'description' => 'Intensive Care Unit',
                'amount' => 500.00,
                'unit' => 'per day',
                'is_taxable' => false,
                'tax_percentage' => 0,
                'is_active' => true,
            ],

            // Emergency Services
            [
                'fee_category_id' => $categories['EMERG']->id,
                'name' => 'Ambulance Service',
                'code' => 'EMERG-AMB-001',
                'description' => 'Emergency ambulance transport',
                'amount' => 200.00,
                'unit' => 'per service',
                'is_taxable' => false,
                'tax_percentage' => 0,
                'is_active' => true,
            ],
            [
                'fee_category_id' => $categories['EMERG']->id,
                'name' => 'Emergency Room Fee',
                'code' => 'EMERG-ER-002',
                'description' => 'Emergency room initial assessment',
                'amount' => 150.00,
                'unit' => 'per visit',
                'is_taxable' => true,
                'tax_percentage' => 5.00,
                'is_active' => true,
            ],
        ];

        foreach ($fees as $fee) {
            Fee::create($fee);
        }
    }
}
