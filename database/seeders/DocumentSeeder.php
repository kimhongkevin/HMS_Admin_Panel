<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Document;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $doctors = User::where('role', 'doctor')->get();
        $staff = User::where('role', 'staff')->get();
        $uploaders = $doctors->merge($staff);

        if ($patients->isEmpty() || $uploaders->isEmpty()) {
            $this->command->warn('Skipping DocumentSeeder: patients or uploaders not found.');
            return;
        }

        $documentTypes = ['medical_record', 'lab_report', 'prescription'];
        $documents = [];

        // Create 20 sample documents
        for ($i = 1; $i <= 20; $i++) {
            $patient = $patients->random();
            $uploader = $uploaders->random();
            $type = $documentTypes[rand(0, count($documentTypes) - 1)];

            $titles = [
                'medical_record' => ['Annual Checkup Report', 'Medical History', 'Consultation Notes'],
                'lab_report'=> ['Blood Test Results', 'X-Ray Report', 'MRI Scan Results', 'Urine Analysis'],
                'prescription' => ['Medication Prescription', 'Treatment Plan', 'Follow-up Prescription'],
            ];

            $title = $titles[$type][rand(0, count($titles[$type]) - 1)];
        $fileName = strtolower(str_replace(' ', '_', $title)) . '_' . time() . rand(1000, 9999) . '.pdf';

        $documents[] = [
            'patient_id' => $patient->id,
            'uploaded_by' => $uploader->id,
            'document_type' => $type,
            'title' => $title,
            'description' => 'Sample ' . str_replace('_', ' ', $type) . ' for ' . $patient->first_name,
            'file_path' => 'documents/' . $fileName,
            'file_name' => $fileName,
            'file_type' => 'application/pdf',
            'file_size' => rand(50000, 500000), // Random size between 50KB - 500KB
            'document_date' => Carbon::now()->subDays(rand(1, 90)),
            'created_at' => Carbon::now()->subDays(rand(1, 90)),
            'updated_at' => now(),
        ];
    }

    foreach ($documents as $document) {
        Document::create($document);
    }
}
}
