<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'department_id',
        'appointment_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    /**
     * Get the patient that owns the appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the doctor that owns the appointment.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the department that owns the appointment.
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Scope a query to only include scheduled appointments.
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include completed appointments.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope a query to only include cancelled appointments.
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope a query to only include upcoming appointments.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('appointment_date', '>', now())
                    ->where('status', 'scheduled');
    }

    /**
     * Scope a query to only include today's appointments.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('appointment_date', today());
    }

    /**
     * Get status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'blue',
            'completed' => 'green',
            'cancelled' => 'red',
            default => 'gray',
        };
    }
}