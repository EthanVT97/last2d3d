<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\HtmlString;
use App\Models\User;

class Play extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'number',
        'amount',
        'status',
        'admin_note'
    ];

    // Status constants
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_WON = 'won';
    const STATUS_LOST = 'lost';

    protected $casts = [
        'amount' => 'decimal:2',
        'resulted_at' => 'datetime'
    ];

    /**
     * Get the user that owns the play.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount) . ' Ks';
    }

    /**
     * Get the type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            '2d' => '2D',
            '3d' => '3D',
            'thai' => 'Thai',
            'laos' => 'Laos',
            default => $this->type
        };
    }

    /**
     * Get the status label
     */
    public function getStatusLabelAttribute(): string
    {
        return [
            'approved' => 'အတည်ပြုပြီး',
            'rejected' => 'ငြင်းပယ်ပြီး',
            'won' => 'ထီပေါက်သည်',
            'lost' => 'မပေါက်ပါ',
        ][$this->status] ?? $this->status;
    }

    /**
     * Get the status badge HTML
     */
    public function getStatusBadgeAttribute(): HtmlString
    {
        $color = [
            'approved' => 'green',
            'rejected' => 'red',
            'won' => 'green',
            'lost' => 'red',
        ][$this->status] ?? 'gray';

        return new HtmlString(
            '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-' . $color . '-100 text-' . $color . '-800">
                ' . $this->status_label . '
            </span>'
        );
    }

    /**
     * Get the type badge HTML
     */
    public function getTypeBadgeAttribute(): HtmlString
    {
        $color = match($this->type) {
            '2d' => 'primary',
            '3d' => 'indigo',
            'thai' => 'blue',
            'laos' => 'green',
            default => 'gray'
        };

        return new HtmlString(
            '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-' . $color . '-100 text-' . $color . '-800">
                ' . $this->type_label . '
            </span>'
        );
    }

    /**
     * Get the winning multiplier
     */
    public function getWinningMultiplierAttribute(): int
    {
        return match($this->type) {
            '2d' => 85,
            '3d' => 500,
            'thai' => 100,
            'laos' => 100,
            default => 0
        };
    }

    /**
     * Calculate potential winning amount
     */
    public function getPotentialWinningAttribute(): float
    {
        return $this->amount * $this->winning_multiplier;
    }

    /**
     * Get the status color for display
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_WON => 'success',
            self::STATUS_LOST => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Get the status text for display
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            self::STATUS_APPROVED => 'အတည်ပြုပြီး',
            self::STATUS_REJECTED => 'ငြင်းပယ်ပြီး',
            self::STATUS_WON => 'ထီပေါက်သည်',
            self::STATUS_LOST => 'မပေါက်ပါ',
            default => $this->status
        };
    }
}
