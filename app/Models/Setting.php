<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'type',
        'value'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    /**
     * Get setting value by key and type
     */
    public static function getValue(string $key, ?string $type = null, mixed $default = null): mixed
    {
        $query = static::where('key', $key);
        if ($type) {
            $query->where('type', $type);
        }
        $setting = $query->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key and type
     */
    public static function setValue(string $key, mixed $value, ?string $type = null): void
    {
        $data = [
            'key' => $key,
            'type' => $type
        ];

        static::updateOrCreate(
            $data,
            ['value' => $value]
        );
    }
}
