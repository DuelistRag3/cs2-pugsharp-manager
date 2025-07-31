<?php

use App\Livewire\Landing;
use App\Livewire\Admin\Login;
use App\Livewire\Admin\Dashboard;
/* Livewire components import */

/* Tournaments Guest */
use App\Livewire\Tournaments\Index as TournamentIndex;
use App\Livewire\Tournaments\Show as TournamentShow;

/* Matches Guest */
use App\Livewire\Matches\Show as MatchShow;
use App\Livewire\Matches\Index as MatchIndex;

/* Tournaments Admin */
use App\Livewire\Admin\Tournaments\Index as AdminTournamentsIndex;
use App\Livewire\Admin\Tournaments\Show as AdminTournamentsShow;
use Illuminate\Support\Facades\Route;

/* Map Admin */
use App\Livewire\Admin\Maps\Index as MapIndex;

/* Server Admin */
use App\Livewire\Admin\Server\Index as ServerIndex;

/* Matches Admin */
use App\Livewire\Admin\Matches\Index as AdminMatchIndex;
use App\Livewire\Admin\Matches\Show as AdminMatchShow; 

Route::get('/', Landing::class)
    ->name('landing');

Route::get('/tournaments', TournamentIndex::class)
    ->name('tournaments.index');

Route::get('/tournaments/{tournament}', TournamentShow::class)
    ->name('tournaments.show');

Route::get('/matches', MatchIndex::class)
    ->name('matches.index');

Route::get('/matches/{match}', MatchShow::class)
    ->name('matches.show');

Route::name('admin.')->prefix('admin')->middleware(['auth'])->group(function () {
    Route::redirect('/', '/admin/dashboard', 301);
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    /* Map routes */
    Route::get('/maps', MapIndex::class)->name('maps.index');

    /* Tournament routes */
    Route::get('/tournaments/overview', AdminTournamentsIndex::class)->name('tournaments.index');
    Route::get('/tournaments/{id}', AdminTournamentsShow::class)->name('tournaments.show');

    /* Server routes */
    Route::get('/servers', ServerIndex::class)->name('server.index');

    /* Match Routes */
    Route::get('/matches', AdminMatchIndex::class)->name('matches.index');
    Route::get('/matches/{id}', AdminMatchShow::class)->name('matches.show');
});

Route::get('/login', Login::class)
    ->name('login');

Route::get('logout', function () {
    auth()->logout();
    return redirect()->route('landing');
})->name('logout');