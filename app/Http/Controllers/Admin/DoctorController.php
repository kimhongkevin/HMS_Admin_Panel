<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class DoctorController extends Controller
{
    /**
     * Display a listing of doctors.
     */
    public function index(Request $request)
    {
        $query = User::with('profile')
            ->where('role', 'doctor');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('employee_id', 'like', "%{$search}%")
                ->orWhereHas('profile', function($q) use ($search) {
                    $q->where('specialization', 'like', "%{$search}%");
                });
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $doctors = $query->orderBy('created_at', 'desc')->paginate(12);

        // Statistics - MAKE SURE THESE LINES ARE HERE
        $totalDoctors = User::where('role', 'doctor')->count();
        $activeDoctors = User::where('role', 'doctor')->where('is_active', true)->count();

        // IMPORTANT: Pass all variables to the view
        return view('admin.doctors.index', compact('doctors', 'totalDoctors', 'activeDoctors'));
    }

    /**
     * Show the form for creating a new doctor.
     */
    public function create()
    {
        return view('admin.doctors.create');
    }

    /**
     * Store a newly created doctor in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'employee_id' => 'required|string|unique:users,employee_id',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'specialization' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'license_number' => 'required|string|unique:user_profiles,license_number',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
            'password' => Hash::make('password123'),
            'role' => 'doctor',
            'is_active' => true,
        ]);

        // Create profile
        $user->profile()->create([
            'phone' => $validated['phone'],
            'address' => $validated['address'] ?? null,
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'gender' => $validated['gender'] ?? null,
            'specialization' => $validated['specialization'],
            'qualification' => $validated['qualification'],
            'license_number' => $validated['license_number'],
        ]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor created successfully. Default password: password123');
    }

    /**
     * Display the specified doctor.
     */
    public function show(User $doctor)
    {
        $doctor->load('profile');
        
        // Get statistics
        $totalPatients = 0; // You can implement based on appointments
        $totalAppointments = 0;
        $completedAppointments = 0;
        $pendingAppointments = 0;

        return view('admin.doctors.show', compact(
            'doctor',
            'totalPatients',
            'totalAppointments',
            'completedAppointments',
            'pendingAppointments'
        ));
    }

    /**
     * Show the form for editing the specified doctor.
     */
    public function edit(User $doctor)
    {
        $doctor->load('profile');
        return view('admin.doctors.edit', compact('doctor'));
    }

    /**
     * Update the specified doctor in storage.
     */
    public function update(Request $request, User $doctor)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($doctor->id)],
            'employee_id' => ['required', 'string', Rule::unique('users')->ignore($doctor->id)],
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'specialization' => 'required|string|max:255',
            'qualification' => 'required|string|max:255',
            'license_number' => ['required', 'string', Rule::unique('user_profiles')->ignore($doctor->profile->id ?? null)],
        ]);

        // Update user
        $doctor->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'employee_id' => $validated['employee_id'],
        ]);

        // Update or create profile
        $doctor->profile()->updateOrCreate(
            ['user_id' => $doctor->id],
            [
                'phone' => $validated['phone'],
                'address' => $validated['address'] ?? null,
                'date_of_birth' => $validated['date_of_birth'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'specialization' => $validated['specialization'],
                'qualification' => $validated['qualification'],
                'license_number' => $validated['license_number'],
            ]
        );

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor updated successfully.');
    }

    /**
     * Remove the specified doctor from storage.
     */
    public function destroy(User $doctor)
    {
        // Deactivate instead of delete
        $doctor->update(['is_active' => false]);

        return redirect()->route('admin.doctors.index')
            ->with('success', 'Doctor deactivated successfully.');
    }

    /**
     * Toggle doctor active status.
     */
    public function toggleStatus(User $doctor)
    {
        $doctor->update(['is_active' => !$doctor->is_active]);

        $status = $doctor->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Doctor {$status} successfully.");
    }
}