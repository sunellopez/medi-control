<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    protected $fillable = [
        'name',
        'description',
        'batch',
        'expiration_date',
        'current_stock',
        'minimum_stock',
        'unit_price',
    ];
}
