<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotterySetting extends Model
{
    protected $fillable = [
        'type',
        'min_amount',
        'max_amount',
        'min_number',
        'max_number',
        'is_active'
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'min_number' => 'integer',
        'max_number' => 'integer',
        'is_active' => 'boolean',
    ];
}
