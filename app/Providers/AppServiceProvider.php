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
        $contype = config('database.default');
        $installed = false;
        if ($contype == "sqlite") {
            if(file_exists(database_path('database.sqlite'))) {
                $installed = true;
            }
        } else if ($contype == "mysql" || $contype == "pgsql") {
            try {
                DB::connection()->getPdo();
                $installed = true;
            } catch (\Exception $e) {
                print($e);
            }
        }
        if ($installed) {
            Config::set('livewire.view_path', resource_path('views/'.Setting::where('key', 'theme')->first()->value.'/livewire'));
            Config::set('view.paths', [resource_path('views/'.Setting::where('key', 'theme')->first()->value)]);
        }
    }
}
