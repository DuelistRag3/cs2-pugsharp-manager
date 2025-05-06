<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::get('/language/{lang}', function ($lang) {
    session(['locale' => $lang]);
    return redirect()->back();
})->name('language');

Route::middleware(['auth'])->name('admin.')->group(function () {

    Route::view('dashboard', 'admin.dashboard')
    ->name('dashboard');

    Route::view('server', 'admin.server')
    ->name('server');

    Route::view('tournaments', 'admin.tournaments')
    ->name('tournaments');

    Route::view('settings', 'admin.settings')
    ->name('settings');

    // Route::redirect('settings', 'settings/app')->name('settings');

    // Volt::route('settings/app', 'settings.app')->name('settings.app');
    // Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    // Volt::route('settings/password', 'settings.password')->name('settings.password');
    // Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__.'/auth.php';
