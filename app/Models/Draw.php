<?php

namespace App\Models;

use App\Services\LotteryManagementService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Draw extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'draw_time',
        'status',
    ];

    protected $casts = [
        'draw_time' => 'datetime',
    ];

    /**
     * Get the result for this draw
     */
    public function result(): HasOne
    {
        return $this->hasOne(LotteryResult::class);
    }

    /**
     * Get all plays for this draw
     */
    public function plays(): HasMany
    {
        return $this->hasMany(Play::class);
    }

    /**
     * Get winning plays for this draw
     */
    public function winningPlays(): HasMany
    {
        return $this->hasMany(Play::class)->where('status', 'won');
    }

    /**
     * Check if draw is open for betting
     */
    public function isOpen(): bool
    {
        return $this->status === 'pending' && $this->draw_time->isFuture();
    }

    /**
     * Get the next draw time
     */
    public static function getNextDrawTime(string $type): \Carbon\Carbon
    {
        return app(LotteryManagementService::class)->getNextDrawTime($type);
    }
}
