<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchAPIController;
use App\Http\Middleware\BearerToken;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::name('api.')->group(function () {

    Route::get('/matches/{id}/config/', [MatchAPIController::class, 'generateMatchConfig'])
        ->name('matches.config')
        ->where('id', '[0-9]+');

    // Bearer secured routes
    Route::middleware([BearerToken::class])->group(function () {
        // Route::get('/matches/{id}/stats/', [MatchAPIController::class, 'roundFinished'])
        //     ->name('matches.stats')
        //     ->where('id', '[0-9]+'); // Ensure ID is numeric

        Route::prefix('matches/{id}/stats')->group(function () {
            Route::get('/', [MatchAPIController::class, 'roundFinished'])
                ->name('matches.stats')
                ->where('id', '[0-9]+'); // Ensure ID is numeric

            Route::post('/golive/{map}', [MatchAPIController::class, 'goLive'])
                ->name('matches.stats.golive')
                ->where(['id' => '[0-9]+', 'map' => '[0-9]+']);

            Route::post('/updateround/{map}', [MatchAPIController::class, 'updateRound'])
                ->name('matches.stats.updateround')
                ->where(['id' => '[0-9]+', 'map' => '[0-9]+']);

            Route::post('/updateplayer/{map}/{steamId}', [MatchAPIController::class, 'updatePlayer'])
                ->name('matches.stats.updateplayer')
                ->where(['id' => '[0-9]+', 'map' => '[0-9]+', 'steamId' => '[0-9]+']);

            Route::post('/finalize/{map}', [MatchAPIController::class, 'finalizeMap'])
                ->name('matches.stats.finalize')
                ->where('id', '[0-9]+'); // Ensure ID is numeric

            Route::post('/finalize', [MatchAPIController::class, 'finalizeMatchup'])
                ->name('matches.stats.finalize')
                ->where(['id' => '[0-9]+', 'round' => '[0-9]+']);
        });

        Route::get('/matches/{id}/demo/upload', [MatchAPIController::class, 'demoUpload'])
            ->name('matches.demo')
            ->where('id', '[0-9]+'); // Ensure ID is numeric
    });
});
