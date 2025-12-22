<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\User;
use App\Models\Department;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::all();
        $doctors = User::where('role', 'doctor')->get();
        $departments = Department::all();

        if ($patients->isEmpty() || $doctors->isEmpty() || $departments->isEmpty()) {
            $this->command->warn('Skipping AppointmentSeeder: patients, doctors, or departments not found.');
            return;
        }

        $appointments = [];

        // Past appointments (completed)
        for ($i = 0; $i < 5; $i++) {
            $appointments[] = [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'department_id' => $departments->random()->id,
                'appointment_date' => Carbon::now()->subDays(rand(1, 30))->setTime(rand(9, 16), rand(0, 1) * 30),
                'status' => 'completed',
                'notes' => 'Regular checkup completed',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Today's appointments (scheduled)
        for ($i = 0; $i < 3; $i++) {
            $appointments[] = [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'department_id' => $departments->random()->id,
                'appointment_date' => Carbon::today()->setTime(rand(9, 16), rand(0, 1) * 30),
                'status' => 'scheduled',
                'notes' => 'Consultation appointment',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Future appointments (scheduled)
        for ($i = 0; $i < 10; $i++) {
            $appointments[] = [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'department_id' => $departments->random()->id,
                'appointment_date' => Carbon::now()->addDays(rand(1, 60))->setTime(rand(9, 16), rand(0, 1) * 30),
                'status' => 'scheduled',
                'notes' => 'Follow-up appointment',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Some cancelled appointments
        for ($i = 0; $i < 2; $i++) {
            $appointments[] = [
                'patient_id' => $patients->random()->id,
                'doctor_id' => $doctors->random()->id,
                'department_id' => $departments->random()->id,
                'appointment_date' => Carbon::now()->addDays(rand(1, 30))->setTime(rand(9, 16), rand(0, 1) * 30),
                'status' => 'cancelled',
                'notes' => 'Patient requested cancellation',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }
    }
}
