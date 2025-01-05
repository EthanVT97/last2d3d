<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LotteryResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'draw_time',
        'numbers',
        'prize_amount',
        'status',
        'created_by',
        'published_at'
    ];

    protected $casts = [
        'draw_time' => 'datetime',
        'published_at' => 'datetime',
        'numbers' => 'array',
        'prize_amount' => 'decimal:2'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
