[file name]: show.blade.php (Updated)
[file content begin]
@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center">
                @if($patient->discharge_status == 'discharged')
                <div class="h-16 w-16 bg-red-100 rounded-full flex items-center justify-center text-red-600 text-2xl font-bold">
                    {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                </div>
                @else
                <div class="h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                </div>
                @endif
                <div class="ml-4">
                    <div class="flex items-center">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $patient->full_name }}</h2>
                        @if($patient->discharge_status == 'discharged')
                        <span class="ml-3 px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                            Discharged
                        </span>
                        @else
                        <span class="ml-3 px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Active
                        </span>
                        @endif
                    </div>
                    <p class="text-gray-500">{{ $patient->patient_id }} • {{ $patient->age }} Years • {{ ucfirst($patient->gender) }}</p>
                    @if($patient->discharge_status == 'discharged' && $patient->discharge_date)
                    <p class="text-sm text-gray-500">Discharged on {{ $patient->discharge_date->format('M d, Y') }}</p>
                    @endif
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('patients.edit', $patient->id) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Edit Profile
                </a>
                @if($patient->discharge_status == 'discharged')
                <form action="{{ route('patients.restore', $patient->id) }}" method="POST" onsubmit="return confirm('Restore this patient to active status?');">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Restore to Active
                    </button>
                </form>
                @else
                <button onclick="showDischargeModal()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Discharge Patient
                </button>
                @endif
            </div>
        </div>

        @if($patient->discharge_status == 'discharged')
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Patient Discharged</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>This patient was discharged on <strong>{{ $patient->discharge_date->format('F d, Y') }}</strong>.</p>
                        @if($patient->discharge_notes)
                        <p class="mt-2"><strong>Discharge Notes:</strong> {{ $patient->discharge_notes }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
                <!-- Contact Details Section -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Contact Details</h3>
                    <ul class="space-y-3 text-sm">
                        <li class="flex justify-between">
                            <span class="text-gray-500">Phone:</span>
                            <span class="font-medium">{{ $patient->phone }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-500">Email:</span>
                            <span class="font-medium">{{ $patient->email ?? 'N/A' }}</span>
                        </li>
                        <li class="flex flex-col mt-2">
                            <span class="text-gray-500 mb-1">Address:</span>
                            <span class="font-medium text-gray-900">{{ $patient->address }}</span>
                        </li>
                    </ul>
                </div>

                <!-- Emergency Contact Section -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Emergency Contact</h3>
                    @if($patient->emergency_contact)
                    <ul class="space-y-3 text-sm">
                        <li class="flex justify-between">
                            <span class="text-gray-500">Name:</span>
                            <span class="font-medium">{{ $patient->emergency_contact['name'] ?? 'N/A' }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-500">Relation:</span>
                            <span class="font-medium">{{ $patient->emergency_contact['relation'] ?? 'N/A' }}</span>
                        </li>
                        <li class="flex justify-between">
                            <span class="text-gray-500">Phone:</span>
                            <span class="font-medium">{{ $patient->emergency_contact['phone'] ?? 'N/A' }}</span>
                        </li>
                    </ul>
                    @else
                    <p class="text-sm text-gray-500">No emergency contact recorded.</p>
                    @endif
                </div>

                <!-- Medical Info Section -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Medical Info</h3>
                    <div class="mb-3">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Blood Group</span>
                        <div class="mt-1 text-lg font-bold text-red-600">{{ $patient->blood_group ?? 'Unknown' }}</div>
                    </div>
                    <div>
                        <span class="text-xs font-semibold text-gray-500 uppercase">History / Allergies</span>
                        <p class="mt-1 text-sm text-gray-700">{{ $patient->medical_history ?? 'None recorded.' }}</p>
                    </div>

                    <!-- Discharge Info if applicable -->
                    @if($patient->discharge_status == 'discharged')
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <span class="text-xs font-semibold text-gray-500 uppercase">Discharge Information</span>
                        <p class="mt-1 text-sm text-gray-700">
                            <strong>Date:</strong> {{ $patient->discharge_date->format('M d, Y') }}<br>
                            @if($patient->discharge_notes)
                            <strong>Notes:</strong> {{ $patient->discharge_notes }}
                            @endif
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2">
                <!-- Rest of the content remains the same -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex">
                            <a href="{{ route('patient.appointments', $patient->id) }}" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                                Appointments
                            </a>
                            <a href="{{ route('patient.documents', $patient->id) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                                Medical Documents
                            </a>
                            <a href="{{ route('patient.invoices', $patient->id) }}" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                                Invoices
                            </a>
                        </nav>
                    </div>

                    <div class="p-6">
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No appointments</h3>
                            <p class="mt-1 text-sm text-gray-500">Get started by creating a new appointment for this patient.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Discharge Modal for show.blade.php -->
<div id="dischargeModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <form action="{{ route('patients.discharge', $patient->id) }}" method="POST">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Discharge Patient</h3>
                <p class="text-sm text-gray-500 mt-1">Discharge {{ $patient->full_name }} from hospital</p>
            </div>

            <div class="px-6 py-4">
                <div class="mb-4">
                    <label for="discharge_date" class="block text-sm font-medium text-gray-700 mb-1">Discharge Date *</label>
                    <input type="date" name="discharge_date" id="discharge_date"
                           value="{{ date('Y-m-d') }}"
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                </div>

                <div class="mb-4">
                    <label for="discharge_notes" class="block text-sm font-medium text-gray-700 mb-1">Discharge Notes</label>
                    <textarea name="discharge_notes" id="discharge_notes" rows="3"
                              class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                              placeholder="Optional: Add discharge summary or follow-up instructions..."></textarea>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex justify-end space-x-3">
                <button type="button" onclick="closeDischargeModal()" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700">
                    Confirm Discharge
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showDischargeModal() {
    const modal = document.getElementById('dischargeModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDischargeModal() {
    const modal = document.getElementById('dischargeModal');
    modal.classList.remove('flex');
    modal.classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('dischargeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeDischargeModal();
    }
});
</script>
@endsection
[file content end]
