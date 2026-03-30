<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class LandingSetting extends Model
{
    protected $fillable = ['key', 'value', 'group', 'label', 'type'];

    public static function getValue(string $key, mixed $default = null): mixed
    {
        return Cache::remember("landing_{$key}", 3600, function () use ($key, $default) {
            $row = static::where('key', $key)->first();
            return $row ? $row->value : $default;
        });
    }

    public static function setValue(string $key, mixed $value): void
    {
        static::where('key', $key)->update(['value' => $value]);
        Cache::forget("landing_{$key}");
    }

    public static function grouped(): \Illuminate\Support\Collection
    {
        return static::query()->get()->groupBy('group');
    }
}

