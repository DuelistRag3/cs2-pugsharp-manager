<?php

use App\Livewire\Landing;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Tournaments\Index as TournamentsIndex;
use Illuminate\Support\Facades\Route;

Route::get('/', Landing::class)
    ->name('landing');

Route::get('/tournaments', Landing::class)
    ->name('tournaments');

Route::name('admin.')->prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
    Route::get('/tournaments/overview', TournamentsIndex::class)->name('tournaments.index');
});

Route::get('/login', Login::class)
    ->name('login');

Route::get('logout', function () {
    auth()->logout();
    return redirect()->route('landing');
})->name('logout');
