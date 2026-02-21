<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Fix for MySQL key length error
        \Illuminate\Support\Facades\Schema::defaultStringLength(191);

        // FORCE ROOT URL (Fix for Emulator/ADB Tunnel)
        if ($this->app->environment('local')) {
            \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));
        }

        // Register Custom Apple Socialite Provider (Fix for signature mismatch)
        \Laravel\Socialite\Facades\Socialite::extend('apple', function ($app) {
            $config = $app['config']['services.apple'];

            return \Laravel\Socialite\Facades\Socialite::buildProvider(
                \App\Socialite\AppleProvider::class, $config
            );
        });

        // Set default values if keys are missing
        $defaults = [
            'site_name' => 'Rezervist',
            'site_tagline' => 'Premium Rezervasyon Platformu',
            'site_description' => 'Türkiye\'nin en seçkin rezervasyon platformu.',
            'site_copyright' => 'Rezervist - Tüm Hakları Saklıdır.',
            'contact_phone' => '0850 555 1234',
            'contact_email' => 'destek@rezervist.com',
            'contact_address' => '',
            'social_facebook' => 'https://facebook.com',
            'social_twitter' => 'https://twitter.com',
            'social_instagram' => 'https://instagram.com',
            'social_whatsapp' => '',
            'system_maintenance' => '0',
            'google_analytics_id' => '',
            'seo_description' => 'Türkiye\'nin en seçkin rezervasyon platformu.',
            'seo_keywords' => 'rezervasyon, online, restoran',
        ];

        // Load global settings, categories, and cuisines
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                // Fetch all settings and group by key for easy access
                $settingsData = \App\Models\Setting::all()->pluck('value', 'key')->toArray();
                $globalSettings = array_merge($defaults, $settingsData);

                // Fetch categories for navbar
                $navbarCategories = \App\Models\Category::where('type', 'business')->take(10)->get();
                $navbarCuisines = \App\Models\Tag::where('category', 'cuisine')->take(10)->get();

                // Load Service Configs (existing logic)
                \App\Services\SettingService::loadToConfig('google', 'services.google');
                \App\Services\SettingService::loadToConfig('apple', 'services.apple');
                \App\Services\SettingService::loadToConfig('iyzico', 'services.iyzico');
                \App\Services\SettingService::loadToConfig('twilio', 'twilio');
                \App\Services\SettingService::loadToConfig('mail', 'mail.mailers.smtp', [
                    'host' => 'host', 'port' => 'port', 'username' => 'username',
                    'password' => 'password', 'encryption' => 'encryption',
                    'from_address' => 'from.address', 'from_name' => 'from.name',
                ]);
            } else {
                $globalSettings = $defaults;
                $navbarCategories = collect();
                $navbarCuisines = collect();
            }
        } catch (\Exception $e) {
            // Silently fail if DB is not ready (during migrations)
            \Log::warning('Could not load settings or data: '.$e->getMessage());
            $globalSettings = $defaults;
            $navbarCategories = collect();
            $navbarCuisines = collect();
        }

        // Share globally with all views
        \Illuminate\Support\Facades\View::share([
            'globalSettings' => $globalSettings ?? $defaults,
            'navbarCategories' => $navbarCategories,
            'navbarCuisines' => $navbarCuisines
        ]);

        // Also set in config for programmatic access
        foreach ($globalSettings ?? $defaults as $key => $value) {
            config(['settings.'.$key => $value]);
        }
    }
}
