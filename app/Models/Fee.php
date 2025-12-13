<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_category_id',
        'name',
        'code',
        'description',
        'amount',
        'unit',
        'is_taxable',
        'tax_percentage',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax_percentage' => 'decimal:2',
            'is_taxable' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function category()
    {
        return $this->belongsTo(FeeCategory::class, 'fee_category_id');
    }

    // Accessors
    public function getTotalAmountAttribute()
    {
        if ($this->is_taxable) {
            $tax = ($this->amount * $this->tax_percentage) / 100;
            return $this->amount + $tax;
        }
        return $this->amount;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('fee_category_id', $categoryId);
    }
}
