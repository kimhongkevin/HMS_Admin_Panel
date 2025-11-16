<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'employee_id' => 'EMP001',
            'is_active' => true,
        ]);

        // Doctor User
        $doctor = User::create([
            'name' => 'Dr. John Doe',
            'email' => 'doctor@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'employee_id' => 'DOC001',
            'is_active' => true,
        ]);

        UserProfile::create([
            'user_id' => $doctor->id,
            'phone' => '1234567890',
            'specialization' => 'Cardiology',
            'qualification' => 'MBBS, MD',
            'license_number' => 'LIC123456',
        ]);

        // Staff User
        User::create([
            'name' => 'Staff Member',
            'email' => 'staff@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'employee_id' => 'STF001',
            'is_active' => true,
        ]);
    }
}
