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

Route::get('/', function () {
    return view('welcome');
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

        // Department Management
        Route::resource('departments', DepartmentController::class);
    });

    // Admin and Staff routes
    Route::middleware(['role:admin,staff'])->group(function () {
        Route::resource('patients', PatientController::class);
        Route::patch('patients/{patient}/toggle-status', [PatientController::class, 'toggleStatus'])
            ->name('patients.toggle-status');
        Route::resource('appointments', AppointmentController::class);
        Route::resource('invoices', InvoiceController::class);
    });

    // Admin, Doctor, and Staff routes
    Route::middleware(['role:admin,doctor,staff'])->group(function () {
        Route::resource('appointments', AppointmentController::class);
        Route::resource('documents', DocumentController::class);
        Route::get('documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::get('patients/{patient}/documents', [DocumentController::class, 'patientDocuments'])->name('patient.documents');
    });
});

require __DIR__.'/auth.php';
