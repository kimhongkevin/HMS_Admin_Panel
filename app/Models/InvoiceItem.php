<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'description', 'quantity', 'unit_price', 'amount'
    ];

    // Auto-calculate amount when creating/updating model
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            $item->amount = $item->quantity * $item->unit_price;
        });
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}