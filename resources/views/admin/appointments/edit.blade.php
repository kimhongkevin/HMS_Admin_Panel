@extends('layouts.admin')

@section('title', 'Edit Appointment')

@section('content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Edit Appointment</h2>
            <p class="text-gray-600 mt-1">Reschedule or update appointment details</p>
        </div>

        <div class="bg-white shadow rounded-lg p-6">
            <form method="POST" action="{{ route('appointments.update', $appointment) }}" id="appointmentForm">
                @csrf
                @method('PUT')

                <!-- Patient Selection -->
                <div class="mb-6">
                    <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                    <select name="patient_id" id="patient_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('patient_id') border-red-500 @enderror">
                        <option value="">Select Patient</option>
                        @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ old('patient_id', $appointment->patient_id) == $patient->id ? 'selected' : '' }}>
                            {{ $patient->patient_id }} -- {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                        @endforeach
                    </select>
                    @error('patient_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Department Selection -->
                <div class="mb-6">
                    <label for="department_id" class="block text-sm font-medium text-gray-700 mb-2">Department *</label>
                    <select name="department_id" id="department_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('department_id') border-red-500 @enderror">
                        <option value="">Select Department</option>
                        @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id', $appointment->department_id) == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                        @endforeach
                    </select>
                    @error('department_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Doctor Selection -->
                <div class="mb-6">
                    <label for="doctor_id" class="block text-sm font-medium text-gray-700 mb-2">Doctor *</label>
                    <select name="doctor_id" id="doctor_id" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('doctor_id') border-red-500 @enderror">
                        <option value="">Select Department First</option>
                    </select>
                    @error('doctor_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date Selection -->
                <div class="mb-6">
                    <label for="appointment_date_picker" class="block text-sm font-medium text-gray-700 mb-2">Appointment Date *</label>
                    <input type="date" name="appointment_date_picker" id="appointment_date_picker" required min="{{ date('Y-m-d') }}" value="{{ old('appointment_date_picker', $appointment->appointment_date->format('Y-m-d')) }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('appointment_date') border-red-500 @enderror">
                    @error('appointment_date')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Time Slot Selection -->
                <div class="mb-6" id="timeSlotsContainer">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Available Time Slots *</label>
                    <div id="timeSlots" class="grid grid-cols-3 md:grid-cols-4 gap-3">
                        <!-- Time slots will be loaded here via AJAX -->
                    </div>
                    <input type="hidden" name="appointment_date" id="appointment_date" value="{{ old('appointment_date', $appointment->appointment_date) }}">
                    <div id="timeSlotsLoading" class="text-center py-4 text-gray-500" style="display: none;">
                        <svg class="animate-spin h-5 w-5 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <p class="mt-2">Loading available time slots...</p>
                    </div>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                    <textarea name="notes" id="notes" rows="4" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Additional notes or special requirements...">{{ old('notes', $appointment->notes) }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('appointments.show', $appointment) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Update Appointment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const departmentSelect = document.getElementById('department_id');
    const doctorSelect = document.getElementById('doctor_id');
    const datePicker = document.getElementById('appointment_date_picker');
    const timeSlotsContainer = document.getElementById('timeSlotsContainer');
    const timeSlots = document.getElementById('timeSlots');
    const timeSlotsLoading = document.getElementById('timeSlotsLoading');
    const appointmentDateInput = document.getElementById('appointment_date');
    const currentTime = '{{ $appointment->appointment_date->format("H:i") }}';

    // Load doctors when department changes
    departmentSelect.addEventListener('change', function() {
        const departmentId = this.value;
        const currentDoctorId = '{{ $appointment->doctor_id }}';
        doctorSelect.disabled = true;
        doctorSelect.innerHTML = '<option value="">Loading doctors...</option>';
        timeSlotsContainer.style.display = 'none';

        if (departmentId) {
            fetch(`{{ route('appointments.doctorsByDepartment') }}?department_id=${departmentId}`)
                .then(response => response.json())
                .then(doctors => {
                    doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
                    doctors.forEach(doctor => {
                        const option = document.createElement('option');
                        option.value = doctor.id;
                        option.textContent = doctor.name;
                        if (doctor.id == currentDoctorId) {
                            option.selected = true;
                        }
                        doctorSelect.appendChild(option);
                    });
                    doctorSelect.disabled = false;
                    loadTimeSlots();
                })
                .catch(error => {
                    console.error('Error loading doctors:', error);
                    doctorSelect.innerHTML = '<option value="">Error loading doctors</option>';
                });
        } else {
            doctorSelect.innerHTML = '<option value="">Select Department First</option>';
        }
    });

    // Load time slots when doctor and date are selected
    function loadTimeSlots() {
        const doctorId = doctorSelect.value;
        const date = datePicker.value;

        if (doctorId && date) {
            timeSlots.style.display = 'none';
            timeSlotsLoading.style.display = 'block';

            fetch(`{{ route('appointments.checkAvailability') }}?doctor_id=${doctorId}&date=${date}`)
                .then(response => response.json())
                .then(data => {
                    timeSlots.innerHTML = '';

                    if (data.available_slots.length === 0) {
                        timeSlots.innerHTML = '<p class="col-span-full text-center text-gray-500">No available time slots for this date.</p>';
                    } else {
                        data.available_slots.forEach(slot => {
                            const button = document.createElement('button');
                            button.type = 'button';
                            button.className = slot.available
                                ? 'px-4 py-2 border border-gray-300 rounded-md text-sm hover:bg-blue-50 hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500'
                                : 'px-4 py-2 border border-gray-200 rounded-md text-sm bg-gray-100 text-gray-400 cursor-not-allowed';
                            button.textContent = slot.display;
                            button.disabled = !slot.available;

                            // Pre-select current appointment time
                            if (slot.time === currentTime) {
                                button.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                                button.classList.remove('border-gray-300');
                            }

                            if (slot.available) {
                                button.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    // Remove active class from all buttons
                                    timeSlots.querySelectorAll('button').forEach(btn => {
                                        btn.classList.remove('bg-blue-600', 'text-white', 'border-blue-600');
                                        btn.classList.add('border-gray-300');
                                    });

                                    // Add active class to clicked button
                                    this.classList.add('bg-blue-600', 'text-white', 'border-blue-600');
                                    this.classList.remove('border-gray-300');

                                    // Set the hidden input value
                                    appointmentDateInput.value = slot.datetime;
                                });
                            }

                            timeSlots.appendChild(button);
                        });
                    }

                    timeSlotsLoading.style.display = 'none';
                    timeSlots.style.display = 'grid';
                })
                .catch(error => {
                    console.error('Error loading time slots:', error);
                    timeSlots.innerHTML = '<p class="col-span-full text-center text-red-500">Error loading time slots. Please try again.</p>';
                    timeSlotsLoading.style.display = 'none';
                    timeSlots.style.display = 'grid';
                });
        }
    }

    // Load time slots on page load
    loadTimeSlots();

    doctorSelect.addEventListener('change', loadTimeSlots);
    datePicker.addEventListener('change', loadTimeSlots);

    // Form validation
    document.getElementById('appointmentForm').addEventListener('submit', function(e) {
        if (!appointmentDateInput.value) {
            e.preventDefault();
            alert('Please select a time slot for the appointment.');
        }
    });
});
</script>
@endsection
