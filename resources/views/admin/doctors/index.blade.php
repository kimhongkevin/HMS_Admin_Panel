@extends('layouts.admin')

@section('title', 'Doctor Management')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="text-3xl font-semibold text-gray-800">Doctor Management</h2>
            <p class="text-gray-600 mt-1">Manage doctor profiles and availability</p>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div class="flex-1">
                    <form method="GET" action="{{ route('admin.doctors.index') }}" class="flex gap-2">
                        <div class="relative flex-1">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search doctors by name, ID, or specialization..."
                                   class="pl-10 pr-4 py-2.5 w-full border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                            Search
                        </button>
                    </form>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.doctors.create') }}"
                       class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Add Doctor</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-5">
                <div class="text-sm text-gray-600 mb-1">Total Doctors</div>
                <div class="text-2xl font-semibold text-gray-900">{{ $totalDoctors }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <div class="text-sm text-gray-600 mb-1">Available</div>
                <div class="text-2xl font-semibold text-gray-900">{{ $activeDoctors }}</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <div class="text-sm text-gray-600 mb-1">Busy</div>
                <div class="text-2xl font-semibold text-gray-900">0</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-5">
                <div class="text-sm text-gray-600 mb-1">On Leave</div>
                <div class="text-2xl font-semibold text-gray-900">{{ $totalDoctors - $activeDoctors }}</div>
            </div>
        </div>

        <!-- Doctors Grid -->
        @if($doctors->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($doctors as $doctor)
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition">
                <!-- Doctor Header -->
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-lg font-semibold text-blue-600">
                                {{ strtoupper(substr($doctor->name, 0, 2)) }}
                            </span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $doctor->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $doctor->employee_id }}</p>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $doctor->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $doctor->is_active ? 'Available' : 'Inactive' }}
                    </span>
                </div>

                <!-- Doctor Details -->
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Specialization</span>
                        <span class="font-medium text-gray-900">{{ $doctor->profile->specialization ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Qualification</span>
                        <span class="font-medium text-gray-900">{{ $doctor->profile->qualification ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">License No.</span>
                        <span class="font-medium text-gray-900">{{ $doctor->profile->license_number ?? 'N/A' }}</span>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="space-y-2 mb-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>{{ $doctor->profile->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="truncate">{{ $doctor->email }}</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-2 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.doctors.show', $doctor) }}"
                       class="flex-1 flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <span class="text-sm">View</span>
                    </a>
                    <a href="{{ route('admin.doctors.edit', $doctor) }}"
                       class="flex-1 flex items-center justify-center gap-2 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                        <svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        <span class="text-sm">Edit</span>
                    </a>
                    <form method="POST" action="{{ route('admin.doctors.destroy', $doctor) }}"
                          onsubmit="return confirm('Are you sure you want to deactivate this doctor?');"
                          class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full flex items-center justify-center gap-2 px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 transition">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span class="text-sm">Delete</span>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $doctors->links() }}
        </div>
        @else
        <div class="bg-white rounded-lg shadow-sm p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">No doctors found</h3>
            <p class="mt-1 text-sm text-gray-500">Get started by creating a new doctor.</p>
            <div class="mt-6">
                <a href="{{ route('admin.doctors.create') }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Doctor
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Success/Error Messages -->
@if(session('success'))
<div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg shadow-lg z-50" role="alert">
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg shadow-lg z-50" role="alert">
    <span class="block sm:inline">{{ session('error') }}</span>
</div>
@endif
@endsection
