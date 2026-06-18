<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand',
        'part_number',
        'unit',
        'stock_quantity',
        'min_stock',
        'price',
        'notes',
    ];
}
