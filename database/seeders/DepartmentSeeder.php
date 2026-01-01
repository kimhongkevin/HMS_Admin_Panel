<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            // Clinical Departments
            ['name' => 'Cardiology', 'code' => 'CARD', 'description' => 'Heart and vascular care, cardiac diagnostics, and treatment'],
            ['name' => 'Pediatrics', 'code' => 'PEDI', 'description' => 'Child and adolescent healthcare from birth to 18 years'],
            ['name' => 'Neurology', 'code' => 'NEUR', 'description' => 'Brain, spinal cord, and nervous system disorders diagnosis and treatment'],
            ['name' => 'Emergency', 'code' => 'ER', 'description' => '24/7 emergency medical services and urgent care'],
            ['name' => 'Laboratory', 'code' => 'LAB', 'description' => 'Medical testing, pathology, and diagnostic services'],
            ['name' => 'Pharmacy', 'code' => 'PHAR', 'description' => 'Medication dispensing, prescription management, and drug information'],
            ['name' => 'Nursery & NICU', 'code' => 'NURS', 'description' => 'Newborn care, neonatal intensive care, and postnatal services'],
            ['name' => 'Orthopedics', 'code' => 'ORTH', 'description' => 'Bone, joint, muscle, and ligament treatment and surgery'],
            ['name' => 'Radiology', 'code' => 'RADI', 'description' => 'X-ray, MRI, CT scans, and medical imaging services'],
            ['name' => 'Dermatology', 'code' => 'DERM', 'description' => 'Skin, hair, and nail conditions treatment'],
            ['name' => 'Oncology', 'code' => 'ONCO', 'description' => 'Cancer diagnosis, chemotherapy, and oncology care'],
            ['name' => 'Gynecology', 'code' => 'GYNO', 'description' => 'Women\'s reproductive health and obstetrics'],
            ['name' => 'ENT', 'code' => 'ENT', 'description' => 'Ear, nose, and throat disorders treatment'],
            ['name' => 'Ophthalmology', 'code' => 'OPTH', 'description' => 'Eye care, vision testing, and ophthalmic surgery'],
            ['name' => 'Psychiatry', 'code' => 'PSYC', 'description' => 'Mental health, behavioral disorders, and psychological care'],
            ['name' => 'Physiotherapy', 'code' => 'PHYS', 'description' => 'Physical rehabilitation and therapeutic exercises'],

            // Administrative & Support Departments
            ['name' => 'Administration', 'code' => 'ADMN', 'description' => 'Hospital management, administration, and executive services'],
            ['name' => 'Human Resources', 'code' => 'HR', 'description' => 'Staff recruitment, training, and personnel management'],
            ['name' => 'Finance & Billing', 'code' => 'FINC', 'description' => 'Financial operations, billing, insurance processing'],
            ['name' => 'Housekeeping', 'code' => 'HKPR', 'description' => 'Sanitation, cleaning, and facility maintenance'],
            ['name' => 'Medical Records', 'code' => 'RECS', 'description' => 'Patient records management and documentation'],
            ['name' => 'IT Department', 'code' => 'IT', 'description' => 'Information technology, systems, and technical support'],
            ['name' => 'Dietary Services', 'code' => 'DIET', 'description' => 'Nutrition planning and food services for patients'],
            ['name' => 'Security', 'code' => 'SECU', 'description' => 'Hospital security and safety management'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept + ['is_active' => true]);
        }
    }
}
