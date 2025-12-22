<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use Illuminate\Support\Str;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patients = [
            [
                'patient_id' => 'PAT-2024-00001',
                'first_name' => 'John',
                'last_name' => 'Smith',
                'email' => 'john.smith@example.com',
                'phone' => '+1234567890',
                'date_of_birth' => '1985-03-15',
                'gender' => 'male',
                'blood_group' => 'A+',
                'address' => '123 Main St, New York, NY 10001',
                'emergency_contact' => json_encode([
                    'name' => 'Jane Smith',
                    'relationship' => 'Wife',
                    'phone' => '+1234567891'
                ]),
                'medical_history' => 'No significant medical history',
            ],
            [
                'patient_id' => 'PAT-2024-00002',
                'first_name' => 'Sarah',
                'last_name' => 'Johnson',
                'email' => 'sarah.johnson@example.com',
                'phone' => '+1234567892',
                'date_of_birth' => '1990-07-22',
                'gender' => 'female',
                'blood_group' => 'B+',
                'address' => '456 Oak Ave, Los Angeles, CA 90001',
                'emergency_contact' => json_encode([
                    'name' => 'Mike Johnson',
                    'relationship' => 'Husband',
                    'phone' => '+1234567893'
                ]),
                'medical_history' => 'Asthma, allergic to penicillin',
            ],
            [
                'patient_id' => 'PAT-2024-00003',
                'first_name' => 'Michael',
                'last_name' => 'Williams',
                'email' => 'michael.williams@example.com',
                'phone' => '+1234567894',
                'date_of_birth' => '1978-11-30',
                'gender' => 'male',
                'blood_group' => 'O+',
                'address' => '789 Pine Rd, Chicago, IL 60601',
                'emergency_contact' => json_encode([
                    'name' => 'Emma Williams',
                    'relationship' => 'Sister',
                    'phone' => '+1234567895'
                ]),
                'medical_history' => 'Hypertension, diabetes type 2',
            ],
            [
                'patient_id' => 'PAT-2024-00004',
                'first_name' => 'Emily',
                'last_name' => 'Brown',
                'email' => 'emily.brown@example.com',
                'phone' => '+1234567896',
                'date_of_birth' => '1995-05-18',
                'gender' => 'female',
                'blood_group' => 'AB+',
                'address' => '321 Elm St, Houston, TX 77001',
                'emergency_contact' => json_encode([
                    'name' => 'Robert Brown',
                    'relationship' => 'Father',
                    'phone' => '+1234567897'
                ]),
                'medical_history' => 'No known allergies',
            ],
            [
                'patient_id' => 'PAT-2024-00005',
                'first_name' => 'David',
                'last_name' => 'Martinez',
                'email' => 'david.martinez@example.com',
                'phone' => '+1234567898',
                'date_of_birth' => '1982-09-25',
                'gender' => 'male',
                'blood_group' => 'A-',
                'address' => '654 Maple Dr, Phoenix, AZ 85001',
                'emergency_contact' => json_encode([
                    'name' => 'Maria Martinez',
                    'relationship' => 'Mother',
                    'phone' => '+1234567899'
                ]),
                'medical_history' => 'Previous heart surgery in 2018',
            ],
            [
                'patient_id' => 'PAT-2024-00006',
                'first_name' => 'Lisa',
                'last_name' => 'Anderson',
                'email' => 'lisa.anderson@example.com',
                'phone' => '+1234567800',
                'date_of_birth' => '1988-12-10',
                'gender' => 'female',
                'blood_group' => 'O-',
                'address' => '987 Birch Ln, Philadelphia, PA 19019',
                'emergency_contact' => json_encode([
                    'name' => 'Tom Anderson',
                    'relationship' => 'Spouse',
                    'phone' => '+1234567801'
                ]),
                'medical_history' => 'Migraine history',
            ],
            [
                'patient_id' => 'PAT-2024-00007',
                'first_name' => 'James',
                'last_name' => 'Taylor',
                'email' => 'james.taylor@example.com',
                'phone' => '+1234567802',
                'date_of_birth' => '1975-04-08',
                'gender' => 'male',
                'blood_group' => 'B-',
                'address' => '147 Cedar St, San Antonio, TX 78201',
                'emergency_contact' => json_encode([
                    'name' => 'Susan Taylor',
                    'relationship' => 'Wife',
                    'phone' => '+1234567803'
                ]),
                'medical_history' => 'Arthritis, high cholesterol',
            ],
            [
                'patient_id' => 'PAT-2024-00008',
                'first_name' => 'Jennifer',
                'last_name' => 'Thomas',
                'email' => 'jennifer.thomas@example.com',
                'phone' => '+1234567804',
                'date_of_birth' => '1992-08-14',
                'gender' => 'female',
                'blood_group' => 'A+',
                'address' => '258 Spruce Ave, San Diego, CA 92101',
                'emergency_contact' => json_encode([
                    'name' => 'Paul Thomas',
                    'relationship' => 'Brother',
                    'phone' => '+1234567805'
                ]),
                'medical_history' => 'No significant medical history',
            ],
            [
                'patient_id' => 'PAT-2024-00009',
                'first_name' => 'Robert',
                'last_name' => 'Jackson',
                'email' => 'robert.jackson@example.com',
                'phone' => '+1234567806',
                'date_of_birth' => '1980-01-20',
                'gender' => 'male',
                'blood_group' => 'O+',
                'address' => '369 Willow Rd, Dallas, TX 75201',
                'emergency_contact' => json_encode([
                    'name' => 'Karen Jackson',
                    'relationship' => 'Mother',
                    'phone' => '+1234567807'
                ]),
                'medical_history' => 'Allergic to shellfish',
            ],
            [
                'patient_id' => 'PAT-2024-00010',
                'first_name' => 'Mary',
                'last_name' => 'White',
                'email' => 'mary.white@example.com',
                'phone' => '+1234567808',
                'date_of_birth' => '1986-06-05',
                'gender' => 'female',
                'blood_group' => 'AB-',
                'address' => '741 Ash Ct, San Jose, CA 95101',
                'emergency_contact' => json_encode([
                    'name' => 'John White',
                    'relationship' => 'Husband',
                    'phone' => '+1234567809'
                ]),
                'medical_history' => 'Thyroid condition under medication',
            ],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }
    }
}
