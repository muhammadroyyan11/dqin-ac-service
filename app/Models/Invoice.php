<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'work_order_id',
        'quotation_id',
        'subtotal',
        'tax',
        'total',
        'paid_amount',
        'status',
        'due_date',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
