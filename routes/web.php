<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\FeeCategoryController;
use App\Http\Controllers\Admin\FeeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    // Register routes
    Route::get('register', [RegisteredUserController::class, 'create'])
                ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    // Login routes
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard - accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin only routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        // Doctor Management
        Route::resource('doctors', DoctorController::class);
        Route::patch('doctors/{doctor}/toggle-status', [DoctorController::class, 'toggleStatus'])
            ->name('doctors.toggle-status');

        // Staff Management
        Route::resource('staff', StaffController::class);
        Route::patch('staff/{staff}/deactivate', [StaffController::class, 'deactivate'])
            ->name('staff.deactivate');

        // Department Management
        Route::resource('departments', DepartmentController::class);

        // Fee Category Management
        Route::resource('fee-categories', FeeCategoryController::class);
        Route::patch('fee-categories/{feeCategory}/toggle-status', [FeeCategoryController::class, 'toggleStatus'])
            ->name('fee-categories.toggle-status');

        // Fee Management
        Route::resource('fees', FeeController::class);
        Route::patch('fees/{fee}/toggle-status', [FeeController::class, 'toggleStatus'])
            ->name('fees.toggle-status');
    });

    // Admin and Staff routes (for discharge feature)
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::post('patients/{patient}/discharge', [PatientController::class, 'discharge'])
            ->name('patients.discharge');
        Route::put('patients/{patient}/restore', [PatientController::class, 'restore'])
            ->name('patients.restore');

        Route::get('/appointments-calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');
        Route::post('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])->name('appointments.updateStatus');
        Route::get('/api/check-availability', [AppointmentController::class, 'checkAvailability'])->name('appointments.checkAvailability');
        Route::get('/api/doctors-by-department', [AppointmentController::class, 'getDoctorsByDepartment'])->name('appointments.doctorsByDepartment');

        Route::patch('invoices/{invoice}/status', [InvoiceController::class, 'updateStatus'])->name('invoices.status');
        Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPDF'])->name('invoices.pdf');
    });

    // Shared routes for Admin, Doctor, and Staff
    Route::middleware(['role:admin,doctor,staff'])->group(function () {
        // Patient routes
        Route::resource('patients', PatientController::class);
        Route::get('patients/{patient}/appointments', [AppointmentController::class, 'patientAppointments'])
            ->name('patient.appointments');
        Route::get('patients/{patient}/documents', [DocumentController::class, 'patientDocuments'])
            ->name('patient.documents');
        Route::get('patients/{patient}/invoices', [InvoiceController::class, 'patientInvoices'])
            ->name('patient.invoices');

        // Appointment routes
        Route::resource('appointments', AppointmentController::class);

        // Document routes
        Route::resource('documents', DocumentController::class);
        Route::get('documents/{document}/download', [DocumentController::class, 'download'])
            ->name('documents.download');

        // Invoice routes
        Route::resource('invoices', InvoiceController::class);
    });

    // AJAX Routes
    Route::middleware(['role:admin,staff,doctor'])->prefix('api')->group(function() {
        Route::get('/get-doctors', [AppointmentController::class, 'getDoctors'])->name('api.doctors');
        Route::get('/get-slots', [AppointmentController::class, 'getSlots'])->name('api.slots');
        Route::get('/fees-by-category', [FeeController::class, 'getByCategory'])->name('api.fees-by-category');
    });
});

require __DIR__.'/auth.php';

