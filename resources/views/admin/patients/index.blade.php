@extends('layouts.admin')

@section('title', 'Patient Management')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <div class="mb-6">
                <h2 class="text-3xl font-semibold text-gray-800">Patient Management</h2>
                <p class="text-gray-600 mt-1">Manage patient information and histories</p>
            </div>
            <div>
                <span class="text-sm text-gray-500">{{ now()->format('l, F j, Y h:i A') }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-5">
                <div class="text-gray-500 text-sm">Total Patients</div>
                <div class="text-2xl font-bold">{{ $totalPatients }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-5">
                <div class="text-gray-500 text-sm">Active</div>
                <div class="text-2xl font-bold">{{ $activePatients }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-5">
                <div class="text-gray-500 text-sm">New Today</div>
                <div class="text-2xl font-bold">{{ $todayPatients }}</div>
            </div>
            <div class="bg-white overflow-hidden shadow-sm rounded-lg p-5">
                <div class="text-gray-500 text-sm">Discharged</div>
                <div class="text-2xl font-bold">{{ $dischargedPatients }}</div>
            </div>
        </div>

        <!-- Add Discharge Status Filter -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 space-y-4 md:space-y-0">
            <div class="w-full md:w-1/3">
                <form action="{{ route('patients.index') }}" method="GET" class="relative">
                    <input type="text" name="search" value="{{ request('search') }}"
                           class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Search patients by name, ID, or phone...">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </form>
            </div>
            <div class="flex space-x-3">
                <!-- Discharge Status Filter Dropdown -->
                <div class="relative">
                    <form action="{{ route('patients.index') }}" method="GET">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <select name="discharge_status" onchange="this.form.submit()"
                                class="appearance-none bg-white border border-gray-300 rounded-lg px-4 py-2 pr-8 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Patients</option>
                            <option value="active" {{ request('discharge_status') == 'active' ? 'selected' : '' }}>Active Patients</option>
                            <option value="discharged" {{ request('discharge_status') == 'discharged' ? 'selected' : '' }}>Discharged Patients</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>
                    </form>
                </div>

                <a href="{{ route('patients.create') }}" class="flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg text-white hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Add Patient
                </a>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age/Gender</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Blood Group</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($patients as $patient)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                            {{ $patient->patient_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                    {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $patient->full_name }}</div>
                                    @if($patient->discharge_status == 'discharged')
                                    <span class="text-xs text-red-600 font-medium">Discharged</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $patient->age }} • {{ ucfirst($patient->gender) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col">
                                <span class="flex items-center"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> {{ $patient->phone }}</span>
                                <span class="flex items-center mt-1"><svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg> {{ $patient->email ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $patient->blood_group ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($patient->discharge_status == 'discharged')
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Discharged
                                </span>
                                @if($patient->discharge_date)
                                <div class="text-xs text-gray-500 mt-1">{{ $patient->discharge_date->format('M d, Y') }}</div>
                                @endif
                            @else
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Active
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-3">
                                <a href="{{ route('patients.show', $patient->id) }}" class="text-blue-600 hover:text-blue-900 mr-3">
                                    View
                                </a>
                                <a href="{{ route('patients.edit', $patient->id) }}" class="text-green-600 hover:text-green-900 mr-3">
                                    Edit
                                </a>
                                @if($patient->discharge_status == 'discharged')
                                    <form action="{{ route('patients.restore', $patient->id) }}" method="POST" onsubmit="return confirm('Restore this patient to active status?');">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-green-600 hover:text-green-900">
                                            Restore
                                        </button>
                                    </form>
                                @else
                                    <button onclick="showDischargeModal({{ $patient->id }}, '{{ $patient->full_name }}')" class="text-gray-600 hover:text-red-900">
                                        Discharge
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No patients found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $patients->links() }}
        </div>
    </div>
</div>

<!-- Discharge Modal -->
<div id="dischargeModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
        <form id="dischargeForm" method="POST">
            @csrf
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Discharge Patient</h3>
                <p class="text-sm text-gray-500 mt-1">Discharge <span id="patientName" class="font-semibold"></span> from hospital</p>
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
function showDischargeModal(patientId, patientName) {
    const modal = document.getElementById('dischargeModal');
    const form = document.getElementById('dischargeForm');
    const nameSpan = document.getElementById('patientName');

    nameSpan.textContent = patientName;
    form.action = `/admin/patients/${patientId}/discharge`;
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

