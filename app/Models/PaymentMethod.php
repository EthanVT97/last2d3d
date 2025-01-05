<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\Auditable;

class PaymentMethod extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'code',
        'type',
        'phone',
        'account_name',
        'min_amount',
        'max_amount',
        'instructions',
        'is_active'
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function getPhoneAttribute($value)
    {
        return $value ? '+95' . $value : null;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }
}
