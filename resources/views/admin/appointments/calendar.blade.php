@extends('layouts.admin')

@section('title', 'Appointments Calendar')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="font-semibold text-3xl text-gray-800 leading-tight">Appointments Calendar</h2>
                <p class="text-gray-600 mt-1">View and manage appointments in calendar view</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    List View
                </a>
                <a href="{{ route('appointments.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Book Appointment
                </a>
            </div>
        </div>

        <!-- Legend -->
        <div class="bg-white shadow rounded-lg p-4 mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <span class="text-sm font-medium text-gray-700">Status:</span>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-blue-500 mr-2"></div>
                    <span class="text-sm text-gray-600">Scheduled</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-green-500 mr-2"></div>
                    <span class="text-sm text-gray-600">Completed</span>
                </div>
                <div class="flex items-center">
                    <div class="w-4 h-4 rounded bg-red-500 mr-2"></div>
                    <span class="text-sm text-gray-600">Cancelled</span>
                </div>
            </div>
        </div>

        <!-- Calendar -->
        <div class="bg-white shadow rounded-lg p-6">
            <div id="calendar"></div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div id="eventModal" class="hidden fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Appointment Details</h3>
                    <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="modalContent" class="space-y-3">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <a id="viewButton" href="#" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    View Details
                </a>
                <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Include FullCalendar from CDN -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.8/index.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var events = typeof events !== 'undefined' ? events : [];

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        events: events,
        eventClick: function(info) {
            showEventDetails(info.event);
        },
        height: 'auto',
        eventTimeFormat: {
            hour: 'numeric',
            minute: '2-digit',
            meridiem: 'short'
        }
    });

    calendar.render();
});

function showEventDetails(event) {
    var modal = document.getElementById('eventModal');
    var modalContent = document.getElementById('modalContent');
    var viewButton = document.getElementById('viewButton');
    var props = event.extendedProps;

    // Set modal content
    modalContent.innerHTML = '<div class="space-y-3">' +
        '<div>' +
        '<span class="text-sm font-medium text-gray-500">Patient:</span>' +
        '<p class="text-sm text-gray-900">' + event.title + '</p>' +
        '</div>' +
        '<div>' +
        '<span class="text-sm font-medium text-gray-500">Doctor:</span>' +
        '<p class="text-sm text-gray-900">' + props.doctor + '</p>' +
        '</div>' +
        '<div>' +
        '<span class="text-sm font-medium text-gray-500">Department:</span>' +
        '<p class="text-sm text-gray-900">' + props.department + '</p>' +
        '</div>' +
        '<div>' +
        '<span class="text-sm font-medium text-gray-500">Date & Time:</span>' +
        '<p class="text-sm text-gray-900">' + event.start.toLocaleString() + '</p>' +
        '</div>' +
        '<div>' +
        '<span class="text-sm font-medium text-gray-500">Status:</span>' +
        '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ' + getStatusClass(props.status) + '">' +
        props.status.charAt(0).toUpperCase() + props.status.slice(1) +
        '</span>' +
        '</div>' +
        '</div>';

    // Set view button link
    viewButton.href = '/appointments/' + event.id;

    // Show modal
    modal.classList.remove('hidden');
}

function closeModal() {
    var modal = document.getElementById('eventModal');
    modal.classList.add('hidden');
}

function getStatusClass(status) {
    switch(status) {
        case 'scheduled':
            return 'bg-blue-100 text-blue-800';
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

// Close modal on escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeModal();
    }
});
</script>
@endsection
