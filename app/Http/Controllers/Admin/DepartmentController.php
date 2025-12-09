<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use App\Models\Appointment;
use App\Http\Requests\Admin\DepartmentStoreRequest;
use App\Http\Requests\Admin\DepartmentUpdateRequest;
use Illuminate\Http\Request;


class DepartmentController extends Controller
{
    /**
     * Apply admin role middleware to all methods.
     */
    // public function __construct()
    // {
    //     // Only admins can manage departments
    //     $this->middleware('role:admin');
    // }

    /**
     * Display a listing of the department.
     * List all departments with head doctor name and appointment count.
     */
    public function index()
    {
        // Load departments with relationships for display and stats
        $departments = Department::with('headDoctor')
            ->withCount('appointments')
            ->orderBy('name')
            ->paginate(10);

        // Calculate total stats for the top cards (similar to dashboard style)
        $totalDepartments = Department::count();
        $activeDepartments = Department::where('is_active', true)->count();
        $totalAppointments = Appointment::count(); // Assuming an Appointment model exists
        $totalDoctors = User::where('role', 'doctor')->count();

        return view('admin.departments.index', compact(
            'departments',
            'totalDepartments',
            'activeDepartments',
            'totalAppointments',
            'totalDoctors'
        ));
    }

    /**
     * Show the form for creating a new department.
     * Form with doctor dropdown.
     */
    public function create()
    {
        // Get only active users with the 'doctor' role for the dropdown
        $doctors = User::where('role', 'doctor')->where('is_active', true)->orderBy('name')->get();
        return view('admin.departments.create', compact('doctors'));
    }

    /**
     * Store a newly created department in storage.
     * Validate and save.
     */
    public function store(DepartmentStoreRequest $request)
    {
        // Validation handled by DepartmentStoreRequest
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']); // Enforce uppercase for code

        Department::create($data);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department "' . $data['name'] . '" created successfully.');
    }

    /**
     * Display the specified department.
     * Department dashboard with statistics, doctors list, appointments.
     */
    public function show(Department $department)
    {
        // Load relationships and statistics
        $department->load(['headDoctor']);

        // --- Department Statistics ---
        $totalAppointments = $department->appointments()->count();
        // Assuming doctors relationship on Department model (many-to-many or via a foreign key in User)
        // For simplicity, we'll assume a 'department_id' on the User model for doctors.
        $assignedDoctors = User::role('doctor')->where('department_id', $department->id)->get();
        $totalDoctors = $assignedDoctors->count();

        // Placeholder for Revenue Generated (requires Invoice/Payment models integration)
        $revenueGenerated = 0;

        // Fetch recent appointments
        $recentAppointments = Appointment::where('department_id', $department->id)
            ->orderByDesc('appointment_date')
            ->take(5)
            ->get();

        return view('admin.departments.show', compact(
            'department',
            'totalAppointments',
            'totalDoctors',
            'assignedDoctors',
            'revenueGenerated',
            'recentAppointments'
        ));
    }

    /**
     * Show the form for editing the specified department.
     */
    public function edit(Department $department)
    {
        // Get only active doctors for the dropdown
        $doctors = User::role('doctor')->where('is_active', true)->orderBy('name')->get();
        return view('admin.departments.edit', compact('department', 'doctors'));
    }

    /**
     * Update the specified department in storage.
     */
    public function update(DepartmentUpdateRequest $request, Department $department)
    {
        // Validation handled by DepartmentUpdateRequest
        $data = $request->validated();
        $data['code'] = strtoupper($data['code']);

        $department->update($data);

        return redirect()->route('admin.departments.index')
            ->with('success', 'Department "' . $department->name . '" updated successfully.');
    }

    /**
     * Deactivate the specified department from storage.
     * Soft delete or deactivate (set is_active=false).
     */
    public function destroy(Department $department)
    {
        // Set is_active to false instead of actual deletion
        $department->is_active = false;
        $department->save();

        return redirect()->route('admin.departments.index')
            ->with('warning', 'Department "' . $department->name . '" has been deactivated.');
    }
}
