<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
    ];

    public static function get(string $key, mixed $default = null): mixed
    {
        try {
            return Cache::remember("setting_{$key}", 3600, function () use ($key, $default) {
                $setting = static::where('key', $key)->first();
                if (! $setting) return $default;

                $val = $setting->value;
                $decoded = json_decode($val, true);
                return json_last_error() === JSON_ERROR_NONE ? $decoded : $val;
            });
        } catch (\Throwable $e) {
            return $default;
        }
    }

    public static function set(string $key, mixed $value, string $group = 'general'): self
    {
        $serialized = is_array($value) || is_object($value) ? json_encode($value) : (string) $value;

        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $serialized, 'group' => $group]
        );

        Cache::forget("setting_{$key}");

        return $setting;
    }
}

