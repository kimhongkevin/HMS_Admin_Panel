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
        $cardiology = Department::where('code', 'CARD')->first();
        $pediatrics = Department::where('code', 'PEDI')->first();
        $emergency = Department::where('code', 'ER')->first();

        // --- ADMINS ---
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@hospital.com',
            'password' => $password,
            'role' => 'admin',
            'employee_id' => 'ADM001',
            'is_active' => true,
        ]);

        // --- DOCTORS ---
        $doctors = [
            [
                'user' => ['name' => 'Dr. Alice Smith', 'email' => 'alice@hospital.com', 'employee_id' => 'DOC001', 'department_id' => $cardiology?->id],
                'profile' => ['specialization' => 'Cardiology', 'qualification' => 'MD, FACC', 'license_number' => 'MED1001']
            ],
            [
                'user' => ['name' => 'Dr. Robert Brown', 'email' => 'robert@hospital.com', 'employee_id' => 'DOC002', 'department_id' => $pediatrics->id],
                'profile' => ['specialization' => 'Pediatrics', 'qualification' => 'MD, DCH', 'license_number' => 'MED1002']
            ],
            [
                'user' => ['name' => 'Dr. Sarah Wilson', 'email' => 'sarah@hospital.com', 'employee_id' => 'DOC003', 'department_id' => $emergency?->id],
                'profile' => ['specialization' => 'Emergency Medicine', 'qualification' => 'MBBS, FACEM', 'license_number' => 'MED1003']
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
                'phone' => '555-010' . $user->id,
                'gender' => 'Other',
                'address' => '123 Medical Way'
            ]));
        }

        // --- STAFF (Non-Doctors) ---
        $staffMembers = [
            ['name' => 'Jane Nurse', 'email' => 'jane@hospital.com', 'employee_id' => 'STF001', 'role' => 'staff'], // Nurse
            ['name' => 'Mark Receptionist', 'email' => 'mark@hospital.com', 'employee_id' => 'STF002', 'role' => 'staff'], // Front Desk
            ['name' => 'Emily Tech', 'email' => 'emily@hospital.com', 'employee_id' => 'STF003', 'role' => 'staff'], // Lab Tech
            ['name' => 'David Pharmacist', 'email' => 'david@hospital.com', 'employee_id' => 'STF004', 'role' => 'staff'], // Pharmacy
        ];

        foreach ($staffMembers as $staff) {
            $user = User::create(array_merge($staff, [
                'password' => $password,
                'is_active' => true,
            ]));

            // Staff also benefit from having a profile for phone/address
            UserProfile::create([
                'user_id' => $user->id,
                'phone' => '555-020' . $user->id,
                'address' => 'Hospital Housing Complex',
                'gender' => 'Other'
            ]);
        }
    }
}
