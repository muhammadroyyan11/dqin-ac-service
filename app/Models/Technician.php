<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Technician extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nik',
        'full_name',
        'phone',
        'address',
        'specialization',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workOrders()
    {
        return $this->belongsToMany(WorkOrder::class, 'work_order_technicians')
            ->withPivot(['is_captain', 'status', 'progress_note', 'completed_at'])
            ->withTimestamps();
    }

    public function serviceReports()
    {
        return $this->hasMany(ServiceReport::class);
    }
}
