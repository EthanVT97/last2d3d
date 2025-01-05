<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_name',
        'account_number',
        'bank_name',
        'status',
        'remarks'
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'deposit_account_id');
    }
}
