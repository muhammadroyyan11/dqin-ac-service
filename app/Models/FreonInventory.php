<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreonInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'stock_quantity',
        'unit',
        'price_per_unit',
        'notes',
    ];
}
