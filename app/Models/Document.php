<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'uploaded_by',
        'document_type',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'document_date',
    ];

    protected $casts = [
        'document_date' => 'date',
    ];

    /**
     * Get the patient that owns the document.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the user who uploaded the document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Helper to format bytes to KB/MB
     */
    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }
    
    /**
     * Helper to format document type label
     */
    public function getTypeLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->document_type));
    }
}