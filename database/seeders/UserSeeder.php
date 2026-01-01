<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Department;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // Get department IDs
        $departments = Department::all()->keyBy('code');

        // --- SUPER ADMIN ---
        $superAdmin = User::create([
            'name' => 'Super Administrator',
            'email' => 'superadmin@hospital.com',
            'password' => $password,
            'role' => 'admin',
            'employee_id' => 'ADM000',
            'department_id' => $departments['ADMN']->id ?? null,
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $superAdmin->id,
            'phone' => '+1-555-000-0000',
            'address' => 'Hospital Headquarters, 1 Medical Center Drive',
            'date_of_birth' => '1980-01-15',
            'gender' => 'male', // ← Changed to lowercase 'male'
            'qualification' => 'MBA, MHA (Master of Health Administration)',
            'specialization' => 'Healthcare Administration',
            'license_number' => 'ADM00001',
        ]);

        // --- ADMINS ---
        $admins = [
            [
                'user' => [
                    'name' => 'System Admin',
                    'email' => 'admin@hospital.com',
                    'employee_id' => 'ADM001',
                    'department_id' => $departments['ADMN']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-001-0001',
                    'date_of_birth' => '1985-03-22',
                    'gender' => 'female', // ← Changed to lowercase 'female'
                    'qualification' => 'MHA, PMP',
                    'specialization' => 'Healthcare Management',
                    'license_number' => 'ADM00101',
                    'address' => '123 Administration Block',
                ]
            ],
            [
                'user' => [
                    'name' => 'Finance Director',
                    'email' => 'finance@hospital.com',
                    'employee_id' => 'ADM002',
                    'department_id' => $departments['FINC']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-001-0002',
                    'date_of_birth' => '1978-07-30',
                    'gender' => 'male',
                    'qualification' => 'CPA, MBA',
                    'specialization' => 'Healthcare Finance',
                    'license_number' => 'FIN00102',
                    'address' => '456 Finance Street',
                ]
            ],
            [
                'user' => [
                    'name' => 'HR Manager',
                    'email' => 'hr@hospital.com',
                    'employee_id' => 'ADM003',
                    'department_id' => $departments['HR']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-001-0003',
                    'date_of_birth' => '1982-11-14',
                    'gender' => 'female',
                    'qualification' => 'MA HRM, SHRM-SCP',
                    'specialization' => 'Healthcare HR',
                    'license_number' => 'HRM00103',
                    'address' => '789 HR Building',
                ]
            ],
        ];

        foreach ($admins as $adminData) {
            $user = User::create(array_merge($adminData['user'], [
                'password' => $password,
                'role' => 'admin',
                'is_active' => true
            ]));

            UserProfile::create(array_merge($adminData['profile'], [
                'user_id' => $user->id,
            ]));
        }

        // --- DOCTORS ---
        $doctors = [
            // Cardiology
            [
                'user' => [
                    'name' => 'Dr. Alice Smith',
                    'email' => 'alice.smith@hospital.com',
                    'employee_id' => 'DOC001',
                    'department_id' => $departments['CARD']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-002-0001',
                    'date_of_birth' => '1975-04-18',
                    'gender' => 'female',
                    'qualification' => 'MD, FACC, PhD Cardiology',
                    'specialization' => 'Interventional Cardiology',
                    'license_number' => 'MED10001',
                    'address' => '101 Heart Care Lane',
                ]
            ],
            [
                'user' => [
                    'name' => 'Dr. Michael Chen',
                    'email' => 'michael.chen@hospital.com',
                    'employee_id' => 'DOC002',
                    'department_id' => $departments['CARD']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-002-0002',
                    'date_of_birth' => '1980-09-25',
                    'gender' => 'male',
                    'qualification' => 'MD, FACC',
                    'specialization' => 'Cardiac Electrophysiology',
                    'license_number' => 'MED10002',
                    'address' => '202 Cardiac Street',
                ]
            ],

            // Pediatrics
            [
                'user' => [
                    'name' => 'Dr. Robert Brown',
                    'email' => 'robert.brown@hospital.com',
                    'employee_id' => 'DOC003',
                    'department_id' => $departments['PEDI']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-002-0003',
                    'date_of_birth' => '1978-12-05',
                    'gender' => 'male',
                    'qualification' => 'MD, DCH, FAAP',
                    'specialization' => 'Pediatric Neurology',
                    'license_number' => 'MED10003',
                    'address' => '303 Child Care Avenue',
                ]
            ],

            // Nursery/NICU
            [
                'user' => [
                    'name' => 'Dr. Priya Sharma',
                    'email' => 'priya.sharma@hospital.com',
                    'employee_id' => 'DOC004',
                    'department_id' => $departments['NURS']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-002-0004',
                    'date_of_birth' => '1982-03-14',
                    'gender' => 'female',
                    'qualification' => 'MD, Neonatology Fellowship',
                    'specialization' => 'Neonatology',
                    'license_number' => 'MED10004',
                    'address' => '404 Newborn Care Road',
                ]
            ],

            // Emergency Medicine
            [
                'user' => [
                    'name' => 'Dr. Sarah Wilson',
                    'email' => 'sarah.wilson@hospital.com',
                    'employee_id' => 'DOC005',
                    'department_id' => $departments['ER']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-002-0005',
                    'date_of_birth' => '1979-06-30',
                    'gender' => 'female',
                    'qualification' => 'MBBS, FACEM, ATLS Certified',
                    'specialization' => 'Emergency Medicine & Trauma',
                    'license_number' => 'MED10005',
                    'address' => '505 Emergency Lane',
                ]
            ],

            // Neurology
            [
                'user' => [
                    'name' => 'Dr. James Lee',
                    'email' => 'james.lee@hospital.com',
                    'employee_id' => 'DOC006',
                    'department_id' => $departments['NEUR']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-002-0006',
                    'date_of_birth' => '1976-08-22',
                    'gender' => 'male',
                    'qualification' => 'MD, PhD Neurology',
                    'specialization' => 'Neurocritical Care',
                    'license_number' => 'MED10006',
                    'address' => '606 Neuro Street',
                ]
            ],

            // Orthopedics
            [
                'user' => [
                    'name' => 'Dr. David Miller',
                    'email' => 'david.miller@hospital.com',
                    'employee_id' => 'DOC007',
                    'department_id' => $departments['ORTH']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-002-0007',
                    'date_of_birth' => '1974-11-11',
                    'gender' => 'male',
                    'qualification' => 'MD, FACS Orthopedics',
                    'specialization' => 'Joint Replacement Surgery',
                    'license_number' => 'MED10007',
                    'address' => '707 Bone & Joint Avenue',
                ]
            ],

            // Radiology
            [
                'user' => [
                    'name' => 'Dr. Lisa Taylor',
                    'email' => 'lisa.taylor@hospital.com',
                    'employee_id' => 'DOC008',
                    'department_id' => $departments['RADI']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-002-0008',
                    'date_of_birth' => '1981-02-28',
                    'gender' => 'female',
                    'qualification' => 'MD, Radiology Residency',
                    'specialization' => 'Interventional Radiology',
                    'license_number' => 'MED10008',
                    'address' => '808 Imaging Boulevard',
                ]
            ],
        ];

        foreach ($doctors as $docData) {
            $user = User::create(array_merge($docData['user'], [
                'password' => $password,
                'role' => 'doctor',
                'is_active' => true
            ]));

            UserProfile::create(array_merge($docData['profile'], [
                'user_id' => $user->id,
            ]));
        }

        // --- STAFF (Non-Doctors) ---
        $staffMembers = [
            // Nursing Staff
            [
                'user' => [
                    'name' => 'Jane Nurse',
                    'email' => 'jane.nurse@hospital.com',
                    'employee_id' => 'NUR001',
                    'role' => 'staff',
                    'department_id' => $departments['NURS']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0001',
                    'date_of_birth' => '1990-05-15',
                    'gender' => 'female',
                    'qualification' => 'BSN, RN',
                    'specialization' => 'Neonatal Nursing',
                    'license_number' => 'NUR10001',
                    'address' => '901 Nursing Quarters',
                ]
            ],
            [
                'user' => [
                    'name' => 'Mark Johnson',
                    'email' => 'mark.johnson@hospital.com',
                    'employee_id' => 'NUR002',
                    'role' => 'staff',
                    'department_id' => $departments['CARD']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0002',
                    'date_of_birth' => '1988-07-20',
                    'gender' => 'male',
                    'qualification' => 'BSN, RN, CCRN',
                    'specialization' => 'Cardiac Care Nursing',
                    'license_number' => 'NUR10002',
                    'address' => '902 Nursing Quarters',
                ]
            ],

            // Front Desk/Reception
            [
                'user' => [
                    'name' => 'Emily Davis',
                    'email' => 'emily.davis@hospital.com',
                    'employee_id' => 'REC001',
                    'role' => 'staff',
                    'department_id' => $departments['ADMN']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0003',
                    'date_of_birth' => '1992-03-10',
                    'gender' => 'female',
                    'qualification' => 'BA Healthcare Administration',
                    'specialization' => 'Patient Relations',
                    'license_number' => 'REC10001',
                    'address' => '101 Reception Hall',
                ]
            ],

            // Laboratory Technicians
            [
                'user' => [
                    'name' => 'Robert Wilson',
                    'email' => 'robert.wilson@hospital.com',
                    'employee_id' => 'LAB001',
                    'role' => 'staff',
                    'department_id' => $departments['LAB']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0004',
                    'date_of_birth' => '1985-09-18',
                    'gender' => 'male',
                    'qualification' => 'MLT (Medical Lab Technician)',
                    'specialization' => 'Hematology',
                    'license_number' => 'LAB10001',
                    'address' => '404 Lab Building',
                ]
            ],

            // Pharmacy
            [
                'user' => [
                    'name' => 'David Pharmacy',
                    'email' => 'david.pharmacy@hospital.com',
                    'employee_id' => 'PHA001',
                    'role' => 'staff',
                    'department_id' => $departments['PHAR']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0005',
                    'date_of_birth' => '1983-12-25',
                    'gender' => 'male',
                    'qualification' => 'PharmD, RPh',
                    'specialization' => 'Clinical Pharmacy',
                    'license_number' => 'PHA10001',
                    'address' => '505 Pharmacy Block',
                ]
            ],

            // IT Support
            [
                'user' => [
                    'name' => 'Alex Tech',
                    'email' => 'alex.tech@hospital.com',
                    'employee_id' => 'IT001',
                    'role' => 'staff',
                    'department_id' => $departments['IT']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0006',
                    'date_of_birth' => '1991-08-08',
                    'gender' => 'other', // ← Changed to 'other' (from 'Non-binary')
                    'qualification' => 'BS Computer Science',
                    'specialization' => 'Healthcare IT Systems',
                    'license_number' => 'IT10001',
                    'address' => '606 IT Center',
                ]
            ],

            // Housekeeping
            [
                'user' => [
                    'name' => 'Maria Garcia',
                    'email' => 'maria.garcia@hospital.com',
                    'employee_id' => 'HKP001',
                    'role' => 'staff',
                    'department_id' => $departments['HKPR']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0007',
                    'date_of_birth' => '1975-04-30',
                    'gender' => 'female',
                    'qualification' => 'Certified Healthcare Environmental Services',
                    'specialization' => 'Hospital Sanitation',
                    'license_number' => 'HKP10001',
                    'address' => '707 Staff Housing',
                ]
            ],

            // Medical Records
            [
                'user' => [
                    'name' => 'Sophia Kim',
                    'email' => 'sophia.kim@hospital.com',
                    'employee_id' => 'REC002',
                    'role' => 'staff',
                    'department_id' => $departments['RECS']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0008',
                    'date_of_birth' => '1987-06-14',
                    'gender' => 'female',
                    'qualification' => 'RHIA (Registered Health Information Admin)',
                    'specialization' => 'Electronic Health Records',
                    'license_number' => 'REC10002',
                    'address' => '808 Records Building',
                ]
            ],

            // Dietary Services
            [
                'user' => [
                    'name' => 'Thomas Cook',
                    'email' => 'thomas.cook@hospital.com',
                    'employee_id' => 'DIE001',
                    'role' => 'staff',
                    'department_id' => $departments['DIET']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0009',
                    'date_of_birth' => '1980-10-05',
                    'gender' => 'male',
                    'qualification' => 'RD (Registered Dietitian)',
                    'specialization' => 'Clinical Nutrition',
                    'license_number' => 'DIE10001',
                    'address' => '909 Dietary Center',
                ]
            ],

            // Security
            [
                'user' => [
                    'name' => 'Kevin Security',
                    'email' => 'kevin.security@hospital.com',
                    'employee_id' => 'SEC001',
                    'role' => 'staff',
                    'department_id' => $departments['SECU']->id ?? null,
                ],
                'profile' => [
                    'phone' => '+1-555-003-0010',
                    'date_of_birth' => '1978-01-20',
                    'gender' => 'male',
                    'qualification' => 'Certified Healthcare Security Officer',
                    'specialization' => 'Hospital Safety & Security',
                    'license_number' => 'SEC10001',
                    'address' => '100 Security Post',
                ]
            ],
        ];

        foreach ($staffMembers as $staffData) {
            $user = User::create(array_merge($staffData['user'], [
                'password' => $password,
                'is_active' => true,
            ]));

            UserProfile::create(array_merge($staffData['profile'], [
                'user_id' => $user->id,
            ]));
        }
    }
}
