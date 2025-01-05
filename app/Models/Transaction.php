<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'status',
        'payment_method',
        'proof',
        'reference_id',
        'approval_level',
        'approved_by',
        'rejected_by',
        'approved_at',
        'rejected_at',
        'admin_note',
        'metadata',
        'deposit_account_id'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function depositAccount()
    {
        return $this->belongsTo(DepositAccount::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'completed' => '<span class="badge bg-success">အတည်ပြုပြီး</span>',
            'pending' => '<span class="badge bg-warning">စစ်ဆေးဆဲ</span>',
            'rejected' => '<span class="badge bg-danger">ငြင်းပယ်ထား</span>',
            default => '<span class="badge bg-secondary">အခြား</span>'
        };
    }

    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'deposit' => 'ငွေသွင်း',
            'withdrawal' => 'ငွေထုတ်',
            default => $this->type
        };
    }

    public function getApprovalLevelTextAttribute(): string
    {
        return match($this->approval_level) {
            'admin' => 'အက်ဒမင်',
            'agent' => 'အေးဂျင့်',
            default => 'အက်ဒမင်'
        };
    }
}
