<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'technician_id',
        'findings',
        'actions_taken',
        'spareparts_used',
        'before_photo',
        'after_photo',
        'customer_notes',
        'customer_signature',
    ];

    protected $casts = [
        'spareparts_used' => 'array',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function technician()
    {
        return $this->belongsTo(Technician::class);
    }
}
