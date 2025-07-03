<?php

use App\Livewire\Landing;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Dashboard;
/* Livewire components import */

/* Tournaments User */
use App\Livewire\Tournaments\Index as TournamentIndex;
use App\Livewire\Tournaments\Show as TournamentShow;

/* Tournaments Admin */
use App\Livewire\Admin\Tournaments\Index as AdminTournamentsIndex;
use App\Livewire\Admin\Tournaments\Show as AdminTournamentsShow;
use Illuminate\Support\Facades\Route;

/* Server Admin */
use App\Livewire\Admin\Server\Index as ServerIndex;

Route::get('/', Landing::class)
    ->name('landing');

Route::get('/tournaments', TournamentIndex::class)
    ->name('tournaments.index');

Route::get('/tournaments/{tournament}', TournamentShow::class)
    ->name('tournaments.show');

Route::name('admin.')->prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    /* Tournament routes */
    Route::get('/tournaments/overview', AdminTournamentsIndex::class)->name('tournaments.index');
    Route::get('/tournaments/{id}', AdminTournamentsShow::class)->name('tournaments.show');

    /* Server routes */
    Route::get('/servers', ServerIndex::class)->name('server.index');
});

Route::get('/login', Login::class)
    ->name('login');

Route::get('/configs/get/{id}', [App\Http\Controllers\GameController::class, 'generateMatchConfig'])
    ->name('configs.get');

Route::get('logout', function () {
    auth()->logout();
    return redirect()->route('landing');
})->name('logout');
