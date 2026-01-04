<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Update the index method to handle discharge filter
    public function index(Request $request)
    {
        $query = Patient::query();

        // Search logic
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                ->orWhere('last_name', 'like', "%{$search}%")
                ->orWhere('patient_id', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by Blood Group
        if ($request->has('blood_group') && $request->blood_group != '') {
            $query->where('blood_group', $request->blood_group);
        }

        // Filter by Discharge Status
        if ($request->has('discharge_status')) {
            $status = $request->discharge_status;
            if ($status == 'discharged') {
                $query->where('discharge_status', 'discharged');
            } elseif ($status == 'active') {
                $query->where('discharge_status', '!=', 'discharged')->orWhereNull('discharge_status');
            }
        }

        $patients = $query->latest()->paginate(10);

        // Stats for the Dashboard Cards (Updated)
        $totalPatients = Patient::count();
        $activePatients = Patient::where('discharge_status', '!=', 'discharged')
                            ->orWhereNull('discharge_status')
                            ->count();
        $todayPatients = Patient::whereDate('created_at', today())->count();
        $dischargedPatients = Patient::where('discharge_status', 'discharged')->count();

        return view('admin.patients.index', compact('patients', 'totalPatients', 'activePatients', 'todayPatients', 'dischargedPatients'));
    }

    // Add new discharge method
    public function discharge(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'discharge_date' => 'required|date',
            'discharge_notes' => 'nullable|string',
        ]);

        $validated['discharge_status'] = 'discharged';

        $patient->update($validated);

        return redirect()->route('patients.show', $patient->id)->with('success', 'Patient discharged successfully.');
    }

    // Add new restore method (to mark as active again)
    public function restore(Patient $patient)
    {
        $patient->update([
            'discharge_status' => null,
            'discharge_date' => null,
            'discharge_notes' => null,
        ]);

        return redirect()->route('patients.show', $patient->id)->with('success', 'Patient restored to active status.');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'address' => 'required|string',
            'emergency_contact.name' => 'nullable|string',
            'emergency_contact.phone' => 'nullable|string',
            'emergency_contact.relation' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $validated['patient_id'] = $this->generatePatientId();
            Patient::create($validated);
        });

        return redirect()->route('patients.index')->with('success', 'Patient registered successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        // Eager load relationships for the dashboard view
        // $patient->load(['appointments', 'invoices', 'documents']);
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:patients,email,'.$patient->id,
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'blood_group' => 'nullable|string|max:5',
            'address' => 'required|string',
            'emergency_contact.name' => 'nullable|string',
            'emergency_contact.phone' => 'nullable|string',
            'emergency_contact.relation' => 'nullable|string',
            'medical_history' => 'nullable|string',
        ]);

        $patient->update($validated);

        return redirect()->route('patients.index')->with('success', 'Patient details updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patients.index')->with('success', 'Patient record deleted successfully.');
    }

    /**
     * Generate Custom Patient ID
     * Format: PAT-YYYY-00001
     */
    private function generatePatientId()
    {
        $year = date('Y');
        $prefix = "PAT-{$year}-";

        $lastPatient = Patient::where('patient_id', 'like', "{$prefix}%")
                              ->orderBy('id', 'desc')
                              ->first();

        if (!$lastPatient) {
            $number = 1;
        } else {
            // Extract the number part
            $parts = explode('-', $lastPatient->patient_id);
            $number = intval(end($parts)) + 1;
        }

        return $prefix . str_pad($number, 5, '0', STR_PAD_LEFT);
    }
}
