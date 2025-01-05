<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'number',
        'session',
        'drawn_at',
        'metadata'
    ];

    protected $casts = [
        'drawn_at' => 'datetime',
        'metadata' => 'array'
    ];
}
