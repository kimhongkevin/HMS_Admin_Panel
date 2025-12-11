<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class StaffController extends Controller
{
    /**
     * Display a listing of the staff.
     */
    public function index(Request $request)
    {
        // Get all departments for filtering/UI purposes
        $departments = Department::all(['id', 'name']);
        
        // Base query for staff users, ordered by creation date
        $query = User::with('profile', 'department')
                     ->where('role', 'staff')
                     ->latest();

        // Search implementation
        $search = $request->query('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('employee_id', 'like', "%{$search}%");
            });
        }

        // Status filter (e.g., is_active)
        $status = $request->query('status');
        if ($status && in_array($status, ['active', 'inactive'])) {
             $query->where('is_active', $status === 'active');
        }

        $staff = $query->paginate(10); // Paginate results

        return view('admin.staff.index', compact('staff', 'departments', 'search', 'status'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create()
    {
        $departments = Department::all(['id', 'name']);
        return view('admin.staff.create', compact('departments'));
    }

    /**
     * Store a newly created staff member in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation Rules
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'employee_id' => [
                'required', 
                'string', 
                'max:20', 
                'unique:users',
                'regex:/^STF-\d{3}$/i' // Format: STF-XXX
            ],
            'department_id' => 'nullable|exists:departments,id',
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
        ]);

        try {
            DB::beginTransaction();

            // 2. Create User
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'staff', // Set role to 'staff'
                'employee_id' => $validated['employee_id'],
                'department_id' => $validated['department_id'] ?? null,
                'is_active' => true,
            ]);

            // 3. Create User Profile
            $user->profile()->create([
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                // Medical fields (qualification, specialization, license_number) are intentionally excluded for staff.
            ]);

            DB::commit();

            return redirect()->route('admin.staff.index')
                             ->with('success', 'Staff member **' . $user->name . '** created successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            // Log the error for debugging
            // \Log::error('Staff creation failed: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Failed to create staff member. Please try again.')
                             ->withInput();
        }
    }

    /**
     * Display the specified staff member.
     */
    public function show(User $staff)
    {
        // Policy or manual check to ensure the user has the 'staff' role
        if (!$staff->isStaff()) {
             abort(404);
        }

        // Eager load necessary relationships
        $staff->load('profile', 'department', 'invoices', 'patients'); 
        
        // Placeholder for activity log/performance metrics - implementation would require additional tables/logic.
        $activityLog = [
             ['action' => 'Registered Patient', 'details' => 'John Doe', 'date' => '2025-11-20'],
             ['action' => 'Created Invoice', 'details' => '#INV-2025-001', 'date' => '2025-11-21'],
        ];

        return view('admin.staff.show', compact('staff', 'activityLog'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(User $staff)
    {
        if (!$staff->isStaff()) {
             abort(404);
        }
        $departments = Department::all(['id', 'name']);
        $staff->load('profile');

        return view('admin.staff.edit', compact('staff', 'departments'));
    }

    /**
     * Update the specified staff member in storage.
     */
    public function update(Request $request, User $staff)
    {
        if (!$staff->isStaff()) {
             abort(404);
        }

        // 1. Validation Rules
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required', 
                'string', 
                'email', 
                'max:255', 
                Rule::unique('users')->ignore($staff->id)
            ],
            'employee_id' => [
                'required', 
                'string', 
                'max:20', 
                Rule::unique('users')->ignore($staff->id),
                'regex:/^STF-\d{3}$/i'
            ],
            'department_id' => 'nullable|exists:departments,id',
            'is_active' => 'required|boolean',
            'password' => 'nullable|string|min:8|confirmed', // Optional password update
            'phone' => 'required|string|max:50',
            'address' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'gender' => ['nullable', 'string', Rule::in(['male', 'female', 'other'])],
        ]);

        try {
            DB::beginTransaction();

            // 2. Update User
            $staff->name = $validated['name'];
            $staff->email = $validated['email'];
            $staff->employee_id = $validated['employee_id'];
            $staff->department_id = $validated['department_id'] ?? null;
            $staff->is_active = $validated['is_active'];
            
            if (!empty($validated['password'])) {
                $staff->password = Hash::make($validated['password']);
            }
            $staff->save();

            // 3. Update/Create User Profile (using updateOrCreate for safety)
            $staff->profile()->updateOrCreate(
                ['user_id' => $staff->id],
                [
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'gender' => $validated['gender'],
                ]
            );

            DB::commit();

            return redirect()->route('admin.staff.index')
                             ->with('success', 'Staff member **' . $staff->name . '** updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            // \Log::error('Staff update failed: ' . $e->getMessage());
            return redirect()->back()
                             ->with('error', 'Failed to update staff member. Please try again.')
                             ->withInput();
        }
    }

    /**
     * Remove the specified staff member from storage.
     */
    public function destroy(User $staff)
    {
        if (!$staff->isStaff()) {
             abort(404);
        }
        
        $name = $staff->name;
        
        $staff->delete();

        return redirect()->route('admin.staff.index')
                         ->with('success', 'Staff member **' . $name . '** deleted successfully.');
    }
}