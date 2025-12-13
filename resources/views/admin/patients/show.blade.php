@extends('layouts.admin')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <div class="flex justify-between items-start mb-6">
            <div class="flex items-center">
                <div class="h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                </div>
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $patient->full_name }}</h2>
                    <p class="text-gray-500">{{ $patient->patient_id }} • {{ $patient->age }} Years • {{ ucfirst($patient->gender) }}</p>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('patients.edit', $patient->id) }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Edit Profile
                </a>
                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create Appointment
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-1 space-y-6">
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
                </div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex">
                            <a href="#" class="border-blue-500 text-blue-600 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                                Appointments
                            </a>
                            <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
                                Medical Documents
                            </a>
                            <a href="#" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm">
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
@endsection