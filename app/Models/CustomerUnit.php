<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'brand',
        'type',
        'pk',
        'serial_number',
        'installation_location',
        'warranty_status',
        'purchase_date',
        'notes',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }
}
