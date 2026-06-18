<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_number',
        'customer_id',
        'customer_unit_id',
        'service_type',
        'description',
        'status',
        'priority',
        'scheduled_date',
        'completed_date',
        'notes',
        'total_estimate',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function customerUnit()
    {
        return $this->belongsTo(CustomerUnit::class);
    }

    public function technicians()
    {
        return $this->belongsToMany(Technician::class, 'work_order_technicians')
            ->withPivot(['is_captain', 'status', 'progress_note', 'completed_at'])
            ->withTimestamps();
    }

    public function captain()
    {
        return $this->belongsToMany(Technician::class, 'work_order_technicians')
            ->withPivot(['is_captain', 'status', 'progress_note', 'completed_at'])
            ->wherePivot('is_captain', true)
            ->withTimestamps();
    }

    public function serviceReports()
    {
        return $this->hasMany(ServiceReport::class);
    }

    public function quotation()
    {
        return $this->hasOne(Quotation::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function complaint()
    {
        return $this->hasOne(Complaint::class);
    }
}
