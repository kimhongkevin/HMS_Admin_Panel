@extends('layouts.admin')

@section('title', 'Appointment Details')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Appointment Details</h2>
                <p class="text-gray-600 mt-1">View appointment information</p>
            </div>
            <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Back to List
            </a>
        </div>

        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        <div class="bg-white shadow rounded-lg overflow-hidden">
            <!-- Status Badge -->
            <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        @if($appointment->status === 'scheduled')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Scheduled</span>
                        @elseif($appointment->status === 'completed')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">Completed</span>
                        @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                        @endif
                    </div>
                    <div class="text-sm text-gray-500">
                        Created {{ $appointment->created_at->diffForHumans() }}
                    </div>
                </div>
            </div>

            <!-- Appointment Information -->
            <div class="px-6 py-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Patient Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Patient Information
                        </h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Name</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Patient Number</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $appointment->patient->patient_number }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $appointment->patient->email }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Phone</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $appointment->patient->phone }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- Doctor & Department Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Appointment Details
                        </h3>
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Doctor</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $appointment->doctor->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Department</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $appointment->department->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Date</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $appointment->appointment_date->format('l, F j, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Time</dt>
                                <dd class="text-sm text-gray-900 mt-1">{{ $appointment->appointment_date->format('g:i A') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Notes -->
                @if($appointment->notes)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Notes</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-wrap">{{ $appointment->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Actions -->
            @if($appointment->status === 'scheduled')
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3">
                <a href="{{ route('appointments.edit', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Appointment
                </a>

                <form method="POST" action="{{ route('appointments.updateStatus', $appointment) }}" class="inline">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Mark as Completed
                    </button>
                </form>

                <form method="POST" action="{{ route('appointments.updateStatus', $appointment) }}" class="inline" onsubmit="return confirm('Are you sure you want to cancel this appointment?')">
                    @csrf
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Cancel Appointment
                    </button>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection