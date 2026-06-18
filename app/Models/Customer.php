<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'email',
        'address',
        'city',
        'notes',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customerUnits()
    {
        return $this->hasMany(CustomerUnit::class);
    }

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function maintenanceContracts()
    {
        return $this->hasMany(MaintenanceContract::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }
}
