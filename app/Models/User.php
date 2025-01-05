<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\Play;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'password',
        'role',
        'status',
        'balance',
        'commission_rate',
        'commission_balance',
        'referral_code',
        'referred_by',
        'points',
        'profile_picture',
        'address',
        'date_of_birth',
        'preferred_payment_method',
        'is_agent',
        'agent_code',
        'agent_commission_rate'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_agent' => 'boolean',
        'commission_rate' => 'decimal:2',
        'commission_balance' => 'decimal:2',
        'points' => 'decimal:2',
        'banned_at' => 'datetime',
        'balance' => 'decimal:2'
    ];

    /**
     * Get the user's transactions
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Hash the password when setting it
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Get the user's activity status
     */
    public function getActivityStatusAttribute(): string
    {
        if (!$this->last_activity_at) {
            return 'inactive';
        }

        $lastActivity = $this->last_activity_at;
        $now = now();

        if ($lastActivity->gt($now->subMinutes(5))) {
            return 'online';
        }

        if ($lastActivity->gt($now->subDay())) {
            return 'today';
        }

        if ($lastActivity->gt($now->subDays(7))) {
            return 'this_week';
        }

        if ($lastActivity->gt($now->subDays(30))) {
            return 'this_month';
        }

        return 'inactive';
    }

    /**
     * Update user balance safely with transaction record
     *
     * @param float $amount
     * @param string $type 'add' or 'subtract'
     * @param string $transactionType Type of transaction (e.g., 'deposit', 'withdrawal', 'bet', 'win')
     * @param array $metadata Additional transaction metadata
     * @return User
     * @throws \Exception
     */
    public function updateBalance(float $amount, string $type = 'add', string $transactionType = null, array $metadata = []): User
    {
        if ($type === 'subtract' && $this->balance < $amount) {
            throw new \Exception('လက်ကျန်ငွေ မလုံလောက်ပါ။');
        }

        DB::transaction(function () use ($amount, $type, $transactionType, $metadata) {
            // Update balance
            $this->balance = $type === 'add' 
                ? $this->balance + $amount 
                : $this->balance - $amount;
            $this->save();

            // Create transaction record
            $this->transactions()->create([
                'type' => $transactionType ?? ($type === 'add' ? 'deposit' : 'withdrawal'),
                'amount' => $amount,
                'status' => 'completed',
                'metadata' => $metadata
            ]);
        });

        return $this;
    }

    /**
     * Update the user's last activity timestamp
     */
    public function updateLastActivity(): void
    {
        $this->last_activity_at = now();
        $this->save();
    }

    /**
     * Get the users who were referred by this user
     */
    public function referrals()
    {
        return $this->hasMany(User::class, 'referred_by', 'id');
    }

    /**
     * Get the user who referred this user
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Get the user's plays
     */
    public function plays(): HasMany
    {
        return $this->hasMany(Play::class);
    }

    /**
     * Increment the user's balance
     */
    public function incrementBalance($amount)
    {
        $this->balance = bcadd($this->balance ?? '0', $amount, 2);
        $this->save();
        return $this->balance;
    }

    /**
     * Decrement the user's balance
     */
    public function decrementBalance($amount)
    {
        if (bccomp($this->balance ?? '0', $amount, 2) < 0) {
            throw new \Exception('Insufficient balance');
        }
        
        $this->balance = bcsub($this->balance ?? '0', $amount, 2);
        $this->save();
        return $this->balance;
    }

    /**
     * Safely update the user's balance and create a transaction record
     *
     * @param float $amount
     * @param string $type
     * @param array $metadata
     * @return void
     * @throws \Exception
     */
    public function subtractBalance(float $amount, string $type = 'bet', array $metadata = []): void
    {
        if ($this->balance < $amount) {
            throw new \Exception('လက်ကျန်ငွေ မလုံလောက်ပါ။');
        }

        DB::transaction(function () use ($amount, $type, $metadata) {
            // Update balance
            $this->balance -= $amount;
            $this->save();

            // Create transaction record
            $this->transactions()->create([
                'type' => $type,
                'amount' => $amount,
                'status' => 'completed',
                'metadata' => $metadata
            ]);
        });
    }

    /**
     * Get formatted balance
     */
    public function getFormattedBalanceAttribute()
    {
        return number_format($this->balance) . ' Ks';
    }

    /**
     * Get the user's activity color for display
     */
    public function getActivityColorAttribute(): string
    {
        return match($this->activity_status) {
            'online' => 'green',
            'today' => 'blue',
            'this_week' => 'indigo',
            'this_month' => 'purple',
            default => 'gray',
        };
    }

    /**
     * Is Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Is Agent
     */
    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    /**
     * Is User
     */
    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    /**
     * Is Banned
     */
    public function isBanned(): bool
    {
        if (!$this->is_banned) {
            return false;
        }

        if ($this->banned_until && $this->banned_until->isPast()) {
            $this->update(['is_banned' => false, 'banned_until' => null]);
            return false;
        }

        return true;
    }

    /**
     * Get Remaining Ban Days
     */
    public function getRemainingBanDays()
    {
        if (!$this->is_banned || !$this->banned_until) {
            return 0;
        }

        return max(0, now()->diffInDays($this->banned_until));
    }

    /**
     * Generate Referral Code
     */
    public function generateReferralCode()
    {
        $code = strtoupper(substr($this->name, 0, 3) . rand(1000, 9999));
        $this->update(['referral_code' => $code]);
        return $code;
    }

    /**
     * Send Password Reset Notification
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomResetPasswordNotification($token));
    }

    /**
     * Update the user's attributes
     */
    public function update(array $attributes = [], array $options = [])
    {
        return parent::update($attributes, $options);
    }

    /**
     * Get the user's notifications
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user's status
     */
    public function getStatusAttribute()
    {
        return $this->banned_at ? 'banned' : 'active';
    }
}
