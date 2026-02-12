<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

class SettingService
{
    /**
     * Get a setting value by key.
     * Caches the result to minimize DB queries.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($key, $default = null)
    {
        return Cache::rememberForever("setting.$key", function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();

            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value.
     * Clears the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  string  $group
     * @return Setting
     */
    public static function set($key, $value, $group = 'general')
    {
        $setting = Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget("setting.$key");

        return $setting;
    }

    /**
     * Load settings into Laravel config with optional mapping.
     *
     * @param  string  $group
     * @param  string|null  $configRoot  e.g. 'services.google' or 'mail.mailers.smtp'
     * @param  array  $map  [db_key_suffix => config_key]
     */
    public static function loadToConfig($group, $configRoot = null, $map = [])
    {
        $settings = Setting::where('group', $group)->get();

        foreach ($settings as $setting) {
            $dbKeySuffix = str_replace($group.'_', '', $setting->key);

            // Determine config key
            $configKeySuffix = $map[$dbKeySuffix] ?? $dbKeySuffix;
            $fullConfigKey = $configRoot ? "$configRoot.$configKeySuffix" : $configKeySuffix;

            config([$fullConfigKey => $setting->value]);
        }
    }
}
