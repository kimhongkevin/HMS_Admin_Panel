<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use App\Models\Document;
use App\Models\Invoice;
use App\Models\Appointment;
use App\Models\Department;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Basic Stats
        $stats = [
            'totalPatients' => Patient::count(),
            'totalDoctors' => User::where('role', 'doctor')->where('is_active', true)->count(),
            'totalDocuments' => Document::count(),
            'todayAppointments' => Appointment::whereDate('appointment_date', today())->count(),
            'totalRevenue' => Invoice::where('status', 'paid')->sum('total'),
            'pendingInvoices' => Invoice::where('status', 'pending')->count(),
        ];

        // Patient Growth Data (Last 6 months)
        $patientGrowth = $this->getPatientGrowthData();

        // Weekly Appointments Data
        $weeklyAppointments = $this->getWeeklyAppointmentsData();

        // Recent Patients (last 5)
        $recentPatients = Patient::with('department')
            ->latest()
            ->limit(5)
            ->get()
            ->map(function($patient) {
                return [
                    'initials' => strtoupper(substr($patient->first_name, 0, 1) . substr($patient->last_name, 0, 1)),
                    'name' => $patient->first_name . ' ' . $patient->last_name,
                    'age' => $patient->date_of_birth ? Carbon::parse($patient->date_of_birth)->age : 'N/A',
                    'department' => $patient->department ? $patient->department->name : 'General',
                    'status' => $this->getPatientStatus($patient),
                    'time' => $patient->created_at->diffForHumans(),
                ];
            });

        // Department Distribution
        $departmentDistribution = $this->getDepartmentDistributionData();

        return view('dashboard', array_merge($stats, [
            'patientGrowth' => $patientGrowth,
            'weeklyAppointments' => $weeklyAppointments,
            'recentPatients' => $recentPatients,
            'departmentDistribution' => $departmentDistribution,
        ]));
    }

    private function getPatientGrowthData()
    {
        $months = [];
        $values = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();

            $count = Patient::whereBetween('created_at', [$monthStart, $monthEnd])->count();

            $months[] = $date->format('M');
            $values[] = $count;
        }

        return [
            'months' => $months,
            'values' => $values,
        ];
    }

    private function getWeeklyAppointmentsData()
    {
        $days = [];
        $appointments = [];

        $startOfWeek = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $day = $startOfWeek->copy()->addDays($i);
            $count = Appointment::whereDate('appointment_date', $day)->count();

            $days[] = $day->format('D');
            $appointments[] = $count;
        }

        return [
            'days' => $days,
            'appointments' => $appointments,
        ];
    }

    private function getDepartmentDistributionData()
    {
        // Get appointments grouped by department
        $distribution = Department::withCount(['appointments' => function($query) {
            $query->where('status', '!=', 'cancelled');
        }])
        ->orderBy('appointments_count', 'desc')
        ->get();

        $totalAppointments = $distribution->sum('appointments_count');

        $data = [];
        $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-yellow-500', 'bg-pink-500', 'bg-indigo-500', 'bg-red-500'];

        foreach ($distribution as $index => $department) {
            if ($totalAppointments > 0) {
                $percentage = round(($department->appointments_count / $totalAppointments) * 100);

                if ($percentage > 0) {
                    $data[] = [
                        'name' => $department->name,
                        'percentage' => $percentage,
                        'color' => $colors[$index % count($colors)] ?? 'bg-gray-500',
                        'count' => $department->appointments_count,
                    ];
                }
            }
        }

        // If there's remaining percentage for "Other"
        $totalPercentage = collect($data)->sum('percentage');
        if ($totalPercentage < 100 && $totalPercentage > 0) {
            $data[] = [
                'name' => 'Other',
                'percentage' => 100 - $totalPercentage,
                'color' => 'bg-gray-300',
                'count' => 0,
            ];
        }

        return $data;
    }

    private function getPatientStatus($patient)
    {
        // This is a simplified status check. You might want to implement more complex logic
        // based on recent appointments, vital signs, etc.

        // Check if patient has recent appointments
        $recentAppointment = Appointment::where('patient_id', $patient->id)
            ->where('status', 'scheduled')
            ->where('appointment_date', '>', now())
            ->orderBy('appointment_date', 'asc')
            ->first();

        if ($recentAppointment) {
            return 'Scheduled';
        }

        // Check last appointment status
        $lastAppointment = Appointment::where('patient_id', $patient->id)
            ->orderBy('appointment_date', 'desc')
            ->first();

        if ($lastAppointment) {
            if ($lastAppointment->status === 'completed') {
                return 'Recovering';
            }
        }

        // Default status based on creation time
        $daysSinceCreation = $patient->created_at->diffInDays(now());

        if ($daysSinceCreation < 7) {
            return 'New';
        }

        return 'Stable';
    }
}

