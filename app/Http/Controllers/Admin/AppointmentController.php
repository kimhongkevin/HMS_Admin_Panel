<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * Display a listing of appointments.
     */
    public function index(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor', 'department'])
            ->orderBy('appointment_date', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        // Filter by doctor
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Filter by patient
        if ($request->filled('patient_id')) {
            $query->where('patient_id', $request->patient_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('patient', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        $appointments = $query->paginate(10);

        // Statistics for cards
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::today()->count();
        $upcomingAppointments = Appointment::upcoming()->count();
        $pendingAppointments = Appointment::scheduled()->count();

        // For filters
        $doctors = User::where('role', 'doctor')->get();
        $patients = Patient::all();

        return view('admin.appointments.index', compact(
            'appointments',
            'totalAppointments',
            'todayAppointments',
            'upcomingAppointments',
            'pendingAppointments',
            'doctors',
            'patients'
        ));
    }

    /**
     * Show the form for creating a new appointment.
     */
    public function create()
    {
        $patients = Patient::orderBy('first_name')->get();
        $departments = Department::orderBy('name')->get();

        return view('admin.appointments.create', compact('patients', 'departments'));
    }

    /**
     * Store a newly created appointment in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        // Check if doctor is available at the requested time
        $isAvailable = $this->checkDoctorAvailability(
            $request->doctor_id,
            $request->appointment_date
        );

        if (!$isAvailable) {
            return back()->withErrors([
                'appointment_date' => 'The selected time slot is not available.'
            ])->withInput();
        }

        Appointment::create($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment scheduled successfully.');
    }

    /**
     * Display the specified appointment.
     */
    public function show(Appointment $appointment)
    {
        $appointment->load(['patient', 'doctor', 'department']);

        return view('admin.appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified appointment.
     */
    public function edit(Appointment $appointment)
    {
        $patients = Patient::orderBy('first_name')->get();
        $departments = Department::orderBy('name')->get();
        $doctors = User::where('role', 'doctor')
            ->where('department_id', $appointment->department_id)
            ->orderBy('name')
            ->get();

        return view('admin.appointments.edit', compact('appointment', 'patients', 'departments', 'doctors'));
    }

    /**
     * Update the specified appointment in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'appointment_date' => 'required|date|after:now',
            'notes' => 'nullable|string',
        ]);

        // Check if doctor is available (exclude current appointment)
        $isAvailable = $this->checkDoctorAvailability(
            $request->doctor_id,
            $request->appointment_date,
            $appointment->id
        );

        if (!$isAvailable) {
            return back()->withErrors([
                'appointment_date' => 'The selected time slot is not available.'
            ])->withInput();
        }

        $appointment->update($validated);

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment updated successfully.');
    }

    /**
     * Remove the specified appointment from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')
            ->with('success', 'Appointment deleted successfully.');
    }

    /**
     * Display calendar view of appointments.
     */
    public function calendar()
    {
        $appointments = Appointment::with(['patient', 'doctor', 'department'])
            ->where('appointment_date', '>=', now()->startOfMonth())
            ->where('appointment_date', '<=', now()->endOfMonth()->addMonth())
            ->where('status', '!=', 'cancelled')
            ->get();

        // Transform for FullCalendar
        $events = $appointments->map(function ($appointment) {
            $patientName = 'N/A';
            if ($appointment->patient) {
                $patientName = $appointment->patient->first_name . ' ' . $appointment->patient->last_name;
            }

            return [
                'id' => $appointment->id,
                'title' => $patientName,
                'start' => $appointment->appointment_date->toIso8601String(),
                'backgroundColor' => $this->getStatusColor($appointment->status),
                'borderColor' => $this->getStatusColor($appointment->status),
                'extendedProps' => [
                    'doctor' => $appointment->doctor->name ?? 'N/A',
                    'department' => $appointment->department->name ?? 'N/A',
                    'status' => $appointment->status,
                ],
            ];
        });

        return view('admin.appointments.calendar', compact('events'));
    }

    /**
     * Check doctor availability via AJAX.
     */
    public function checkAvailability(Request $request)
    {
        $doctorId = $request->doctor_id;
        $date = $request->date;

        if (!$doctorId || !$date) {
            return response()->json(['error' => 'Missing required parameters'], 400);
        }

        $availableSlots = $this->getAvailableTimeSlots($doctorId, $date);

        return response()->json([
            'available_slots' => $availableSlots
        ]);
    }

    /**
     * Get doctors by department via AJAX.
     */
    public function getDoctorsByDepartment(Request $request)
    {
        $departmentId = $request->department_id;

        $doctors = User::where('role', 'doctor')
            ->where('department_id', $departmentId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($doctors);
    }

    /**
     * Update appointment status.
     */
    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:scheduled,completed,cancelled'
        ]);

        $appointment->update($validated);

        return back()->with('success', 'Appointment status updated successfully.');
    }

    /**
     * Check if doctor is available at the requested time.
     */
    private function checkDoctorAvailability($doctorId, $appointmentDate, $excludeId = null)
    {
        $appointmentTime = \Carbon\Carbon::parse($appointmentDate);

        // Check working hours (9 AM - 5 PM)
        if ($appointmentTime->hour < 9 || $appointmentTime->hour >= 17) {
            return false;
        }

        // Check for overlapping appointments (30-minute slots)
        $startTime = $appointmentTime->copy();
        $endTime = $appointmentTime->copy()->addMinutes(30);

        $query = Appointment::where('doctor_id', $doctorId)
            ->where('status', '!=', 'cancelled')
            ->where(function($q) use ($startTime, $endTime) {
                $q->whereBetween('appointment_date', [$startTime, $endTime])
                  ->orWhere(function($q2) use ($startTime, $endTime) {
                      $q2->where('appointment_date', '<=', $startTime)
                         ->whereRaw('DATE_ADD(appointment_date, INTERVAL 30 MINUTE) > ?', [$startTime]);
                  });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->count() === 0;
    }

    /**
     * Get available time slots for a doctor on a specific date.
     */
    private function getAvailableTimeSlots($doctorId, $date)
    {
        $slots = [];
        $startHour = 9; // 9 AM
        $endHour = 17; // 5 PM
        $slotDuration = 30; // minutes

        $date = \Carbon\Carbon::parse($date);

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += $slotDuration) {
                $slotTime = $date->copy()->setTime($hour, $minute);

                // Skip past time slots
                if ($slotTime->isPast()) {
                    continue;
                }

                $isAvailable = $this->checkDoctorAvailability($doctorId, $slotTime);

                $slots[] = [
                    'time' => $slotTime->format('H:i'),
                    'display' => $slotTime->format('g:i A'),
                    'datetime' => $slotTime->toDateTimeString(),
                    'available' => $isAvailable,
                ];
            }
        }

        return $slots;
    }

    /**
     * Get status color for calendar.
     */
    private function getStatusColor($status)
    {
        return match($status) {
            'scheduled' => '#3B82F6',
            'completed' => '#10B981',
            'cancelled' => '#EF4444',
            default => '#6B7280',
        };
    }

    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }
}
