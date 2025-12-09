@extends('layouts.admin')

@section('title', 'Department Overview: ' . $department->name)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">
                Department: {{ $department->name }} ({{ $department->code }})
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('admin.departments.edit', $department) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Edit Department
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Total Appointments</dt>
                    <dd class="text-3xl font-semibold text-gray-900">{{ $totalAppointments }}</dd>
                </dl>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Assigned Doctors</dt>
                    <dd class="text-3xl font-semibold text-gray-900">{{ $totalDoctors }}</dd>
                </dl>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Revenue Generated</dt>
                    <dd class="text-3xl font-semibold text-gray-900">${{ number_format($revenueGenerated, 2) }}</dd>
                </dl>
            </div>
            <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                <dl>
                    <dt class="text-sm font-medium text-gray-500 truncate">Head Doctor</dt>
                    <dd class="text-xl font-semibold text-gray-900 mt-1">{{ $department->headDoctor ? $department->headDoctor->name : 'Unassigned' }}</dd>
                </dl>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Assigned Doctors ({{ $totalDoctors }})</h3>
                    <div class="space-y-4">
                        @forelse ($assignedDoctors as $doctor)
                            <div class="flex items-center justify-between p-3 border-b border-gray-100 last:border-b-0">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $doctor->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $doctor->email }}</p>
                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Doctor
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No doctors currently assigned to this department.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1 bg-white shadow rounded-lg overflow-hidden">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4">Recent Appointments</h3>
                    <div class="space-y-4">
                        @forelse ($recentAppointments as $appointment)
                            <div class="border-l-4 border-purple-500 pl-3 py-2 bg-gray-50 rounded-md">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y H:i') }}
                                </p>
                                <p class="text-xs text-gray-600">Patient: {{ $appointment->patient->name ?? 'N/A' }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No recent appointments found.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection