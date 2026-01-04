<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory, SoftDeletes;

    // Add to Patient.php model's $fillable array
    protected $fillable = [
        'patient_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'blood_group',
        'address',
        'emergency_contact',
        'medical_history',
        'discharge_status', // Add this
        'discharge_date',   // Add this
        'discharge_notes',  // Add this
    ];

    // Add to $casts array
    protected $casts = [
        'date_of_birth' => 'date',
        'discharge_date' => 'datetime', // Add this
        'emergency_contact' => 'array',
    ];

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return Carbon::parse($this->date_of_birth)->age;
    }

    // Relationships
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
