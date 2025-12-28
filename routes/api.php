<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AppointmentApiController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

/**
 * Public Appointment API Routes
 * No authentication required
 * Base URL: /api/public
 */

Route::middleware('throttle:60,1')->prefix('public')->name('public.')->group(function () {

    // Get departments
    Route::get('/departments', [AppointmentApiController::class, 'getDepartments'])
        ->name('departments');

    // Get doctors by department
    Route::get('/doctors', [AppointmentApiController::class, 'getDoctorsByDepartment'])
        ->name('doctors');

    // Check availability and get time slots
    Route::get('/availability', [AppointmentApiController::class, 'checkAvailability'])
        ->name('availability');

    // Appointment endpoints
    Route::prefix('/appointments')->name('appointments.')->group(function () {
        // Book new appointment
        Route::post('/', [AppointmentApiController::class, 'bookAppointment'])
            ->name('book');

        // Get appointment details
        Route::get('/{id}', [AppointmentApiController::class, 'getAppointment'])
            ->name('show');

        // Cancel appointment
        Route::patch('/{id}/cancel', [AppointmentApiController::class, 'cancelAppointment'])
            ->name('cancel');

        // Reschedule appointment
        Route::patch('/{id}/reschedule', [AppointmentApiController::class, 'rescheduleAppointment'])
            ->name('reschedule');
    });
});
