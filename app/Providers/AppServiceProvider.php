<?php

namespace App\Providers;

use App\Models\Setting;
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
        // Load settings from the database if the manager is installed
        // and set them in the config
        if (config('manager.installed')) {
            config([
                'settings' => Setting::all(['key', 'value'])
                ->keyBy('key')
                ->transform(function ($setting) {
                    return $setting->value;
                })
                ->toArray()
            ]);
        }
    }
}
