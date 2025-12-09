@extends('layouts.admin')

@section('title', 'Doctor Details')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center gap-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.doctors.index') }}" class="hover:text-blue-600">Doctors</a>
                <span>/</span>
                <span class="text-gray-900">Doctor Details</span>
            </div>
            <div class="flex items-center justify-between">
                <h2 class="text-3xl font-semibold text-gray-800">Doctor Details</h2>
                <div class="flex gap-3">
                    <a href="{{ route('admin.doctors.edit', $doctor) }}" 
                       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Doctor
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Doctor Profile -->
            <div class="lg:col-span-1">
                <!-- Profile Card -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="text-center">
                        <div class="w-24 h-24 mx-auto rounded-full bg-blue-100 flex items-center justify-center mb-4">
                            <span class="text-3xl font-semibold text-blue-600">
                                {{ strtoupper(substr($doctor->name, 0, 2)) }}
                            </span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900">{{ $doctor->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $doctor->employee_id }}</p>
                        <div class="mt-3">
                            <span class="px-4 py-1.5 text-sm font-medium rounded-full {{ $doctor->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $doctor->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 space-y-4">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Specialization</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $doctor->profile->specialization ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">Qualification</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $doctor->profile->qualification ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-1">License Number</p>
                            <p class="text-sm text-gray-900 font-medium">{{ $doctor->profile->license_number ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Contact Information Card -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Contact Information</h4>
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Email</p>
                                <a href="mailto:{{ $doctor->email }}" class="text-sm text-blue-600 hover:underline">
                                    {{ $doctor->email }}
                                </a>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Phone</p>
                                <a href="tel:{{ $doctor->profile->phone ?? '' }}" class="text-sm text-gray-900">
                                    {{ $doctor->profile->phone ?? 'N/A' }}
                                </a>
                            </div>
                        </div>
                        @if($doctor->profile && $doctor->profile->address)
                        <div class="flex items-start gap-3">
                            <svg class="h-5 w-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-xs text-gray-500 mb-1">Address</p>
                                <p class="text-sm text-gray-900">{{ $doctor->profile->address }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Statistics and Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Total Appointments</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $statistics['total_appointments'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Completed</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $statistics['completed_appointments'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Pending</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $statistics['pending_appointments'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-5">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600 mb-1">Total Patients</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $statistics['total_patients'] }}</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Date of Birth</p>
                            <p class="text-sm text-gray-900">
                                {{ $doctor->profile && $doctor->profile->date_of_birth ? \Carbon\Carbon::parse($doctor->profile->date_of_birth)->format('F d, Y') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Gender</p>
                            <p class="text-sm text-gray-900">
                                {{ $doctor->profile && $doctor->profile->gender ? ucfirst($doctor->profile->gender) : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Member Since</p>
                            <p class="text-sm text-gray-900">{{ $doctor->created_at->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide mb-2">Last Updated</p>
                            <p class="text-sm text-gray-900">{{ $doctor->updated_at->format('F d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Placeholder -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h4>
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="mt-2 text-sm text-gray-600">No recent activity to display</p>
                        <p class="text-xs text-gray-500 mt-1">Activity logs will appear here once appointments are scheduled</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif
@endsection