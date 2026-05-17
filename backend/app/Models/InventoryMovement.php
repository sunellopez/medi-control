<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'medication_id',
        'type',
        'quantity',
        'user_id',
        'reason',
    ];
}
