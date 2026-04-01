<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppConfig extends Model
{
    use \App\Traits\Auditable;
    protected $connection = 'central';

    protected $fillable = ['key', 'value', 'group', 'label', 'type'];

    /**
     * Ambil nilai config by key, dengan fallback default.
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return Cache::remember("app_config_{$key}", 3600, function () use ($key, $default) {
            $config = static::where('key', $key)->first();
            return $config ? $config->value : $default;
        });
    }

    /**
     * Simpan/perbarui nilai config by key dan reset cache.
     */
    public static function set(string $key, mixed $value): void
    {
        $config = static::where('key', $key)->first();
        if ($config) {
            $config->update(['value' => $value]);
        } else {
            static::create(['key' => $key, 'value' => $value]);
        }
        Cache::forget("app_config_{$key}");
    }

    /**
     * Ambil semua config sebagai key-value collection.
     */
    public static function all($columns = ['*'])
    {
        return parent::all($columns)->pluck('value', 'key');
    }

    /**
     * Ambil semua config grouped by 'group'.
     */
    public static function grouped(): \Illuminate\Database\Eloquent\Collection
    {
        return parent::all()->groupBy('group');
    }
}
