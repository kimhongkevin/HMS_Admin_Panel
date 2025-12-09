<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'head_doctor_id',
        'is_active',
    ];

    /**
     * Relationship with the Head Doctor (User model).
     */
    public function headDoctor(): BelongsTo
    {
        // head_doctor_id is a foreignId constrained to users 
        return $this->belongsTo(User::class, 'head_doctor_id');
    }

    /**
     * Relationship with Appointments (Appointment model).
     */
    public function appointments(): HasMany
    {
        // Assuming Appointment model has a 'department_id' foreign key 
        return $this->hasMany(Appointment::class);
    }

    /**
     * Scope for active departments.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}