<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Department;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AppointmentApiController extends Controller
{
    /**
     * Get all available departments
     * GET /api/public/departments
     */
    public function getDepartments()
    {
        try {
            $departments = Department::where('is_active', true)
                ->select('id', 'name', 'code', 'description')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $departments,
                'message' => 'Departments retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving departments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get doctors by department
     * GET /api/public/doctors?department_id=1
     */
    public function getDoctorsByDepartment(Request $request)
    {
        try {
            $validated = $request->validate([
                'department_id' => 'required|exists:departments,id'
            ]);

            $doctors = User::where('role', 'doctor')
                ->where('department_id', $validated['department_id'])
                ->where('is_active', true)
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $doctors,
                'message' => 'Doctors retrieved successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving doctors',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check doctor availability and get available time slots
     * GET /api/public/availability?doctor_id=1&date=2025-01-15
     */
    public function checkAvailability(Request $request)
    {
        try {
            $validated = $request->validate([
                'doctor_id' => 'required|exists:users,id',
                'date' => 'required|date_format:Y-m-d|after_or_equal:today'
            ]);

            $doctor = User::where('id', $validated['doctor_id'])
                ->where('role', 'doctor')
                ->where('is_active', true)
                ->firstOrFail();

            $availableSlots = $this->getAvailableTimeSlots(
                $validated['doctor_id'],
                $validated['date']
            );

            return response()->json([
                'success' => true,
                'doctor' => $doctor->only(['id', 'name', 'email']),
                'date' => $validated['date'],
                'available_slots' => $availableSlots,
                'message' => count($availableSlots) > 0
                    ? 'Available slots found'
                    : 'No available slots for this date'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error checking availability',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Book a new appointment
     * POST /api/public/appointments
     */
    public function bookAppointment(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'required|string|max:20',
                'doctor_id' => 'required|exists:users,id',
                'department_id' => 'required|exists:departments,id',
                'appointment_date' => 'required|date|after:now',
                'notes' => 'nullable|string|max:500'
            ]);

            // Verify doctor belongs to department
            $doctor = User::where('id', $validated['doctor_id'])
                ->where('department_id', $validated['department_id'])
                ->where('role', 'doctor')
                ->firstOrFail();

            // Check doctor availability
            $isAvailable = $this->checkDoctorAvailability(
                $validated['doctor_id'],
                $validated['appointment_date']
            );

            if (!$isAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected time slot is not available'
                ], 422);
            }

            // Create or get patient
            $patient = Patient::firstOrCreate(
                ['email' => $validated['email']],
                [
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'],
                ]
            );

            // Create appointment
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $validated['doctor_id'],
                'department_id' => $validated['department_id'],
                'appointment_date' => $validated['appointment_date'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'scheduled'
            ]);

            return response()->json([
                'success' => true,
                'data' => $this->formatAppointmentResponse($appointment),
                'message' => 'Appointment booked successfully'
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error booking appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get appointment details
     * GET /api/public/appointments/{id}?email=user@example.com
     */
    public function getAppointment($id, Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email'
            ]);

            $appointment = Appointment::with(['patient', 'doctor', 'department'])
                ->whereHas('patient', function($q) use ($validated) {
                    $q->where('email', $validated['email']);
                })
                ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $this->formatAppointmentResponse($appointment),
                'message' => 'Appointment retrieved successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Appointment not found or unauthorized access',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Cancel appointment
     * PATCH /api/public/appointments/{id}/cancel
     */
    public function cancelAppointment($id, Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'reason' => 'nullable|string|max:500'
            ]);

            $appointment = Appointment::with(['patient'])
                ->whereHas('patient', function($q) use ($validated) {
                    $q->where('email', $validated['email']);
                })
                ->findOrFail($id);

            // Check if appointment can be cancelled (not already completed/cancelled)
            if ($appointment->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot cancel a completed appointment'
                ], 422);
            }

            $appointment->update([
                'status' => 'cancelled',
                'notes' => ($appointment->notes ?? '') . "\nCancellation reason: " . ($validated['reason'] ?? 'No reason provided')
            ]);

            return response()->json([
                'success' => true,
                'data' => $this->formatAppointmentResponse($appointment),
                'message' => 'Appointment cancelled successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error cancelling appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reschedule appointment
     * PATCH /api/public/appointments/{id}/reschedule
     */
    public function rescheduleAppointment($id, Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'appointment_date' => 'required|date|after:now'
            ]);

            $appointment = Appointment::with(['patient'])
                ->whereHas('patient', function($q) use ($validated) {
                    $q->where('email', $validated['email']);
                })
                ->findOrFail($id);

            if ($appointment->status === 'completed') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot reschedule a completed appointment'
                ], 422);
            }

            // Check new time slot availability
            $isAvailable = $this->checkDoctorAvailability(
                $appointment->doctor_id,
                $validated['appointment_date'],
                $appointment->id
            );

            if (!$isAvailable) {
                return response()->json([
                    'success' => false,
                    'message' => 'Selected time slot is not available'
                ], 422);
            }

            $appointment->update([
                'appointment_date' => $validated['appointment_date']
            ]);

            return response()->json([
                'success' => true,
                'data' => $this->formatAppointmentResponse($appointment),
                'message' => 'Appointment rescheduled successfully'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error rescheduling appointment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Private helper: Check doctor availability
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
     * Private helper: Get available time slots
     */
    private function getAvailableTimeSlots($doctorId, $date)
    {
        $slots = [];
        $startHour = 9;
        $endHour = 17;
        $slotDuration = 30;

        $date = \Carbon\Carbon::parse($date);

        for ($hour = $startHour; $hour < $endHour; $hour++) {
            for ($minute = 0; $minute < 60; $minute += $slotDuration) {
                $slotTime = $date->copy()->setTime($hour, $minute);

                // Skip past time slots
                if ($slotTime->isPast()) {
                    continue;
                }

                $isAvailable = $this->checkDoctorAvailability($doctorId, $slotTime);

                if ($isAvailable) {
                    $slots[] = [
                        'time' => $slotTime->format('H:i'),
                        'display' => $slotTime->format('g:i A'),
                        'datetime' => $slotTime->toDateTimeString()
                    ];
                }
            }
        }

        return $slots;
    }

    /**
     * Private helper: Format appointment response
     */
    private function formatAppointmentResponse($appointment)
    {
        return [
            'id' => $appointment->id,
            'appointment_date' => $appointment->appointment_date->format('Y-m-d H:i'),
            'status' => $appointment->status,
            'notes' => $appointment->notes,
            'patient' => [
                'id' => $appointment->patient->id,
                'first_name' => $appointment->patient->first_name,
                'last_name' => $appointment->patient->last_name,
                'email' => $appointment->patient->email,
                'phone' => $appointment->patient->phone ?? null
            ],
            'doctor' => [
                'id' => $appointment->doctor->id,
                'name' => $appointment->doctor->name,
                'email' => $appointment->doctor->email
            ],
            'department' => [
                'id' => $appointment->department->id,
                'name' => $appointment->department->name,
                'code' => $appointment->department->code
            ],
            'created_at' => $appointment->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $appointment->updated_at->format('Y-m-d H:i:s')
        ];
    }
}
