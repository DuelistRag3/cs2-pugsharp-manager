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
