<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
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
        if (DB::connection()->getDatabaseName()) {
            Config::set('livewire.view_path', resource_path('views/'.Setting::where('key', 'theme')->first()->value.'/livewire'));
            Config::set('view.paths', [resource_path('views/'.Setting::where('key', 'theme')->first()->value)]);
        }
    }
}
