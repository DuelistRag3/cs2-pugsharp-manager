<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MatchAPIController;
use App\Http\Middleware\BearerToken;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::name('api.')->prefix('/api')->group(function () {

    Route::get('/matches/{id}/config/', [MatchAPIController::class, 'generateMatchConfig'])
        ->name('matches.config')
        ->where('id', '[0-9]+');

    // Bearer secured routes
    Route::middleware([BearerToken::class])->group(function () {
        Route::get('/matches/{id}/stats/', [MatchAPIController::class, 'roundFinished'])
            ->name('matches.stats')
            ->where('id', '[0-9]+'); // Ensure ID is numeric

        Route::get('/matches/{id}/demo/upload', [MatchAPIController::class, 'demoUpload'])
            ->name('matches.demo')
            ->where('id', '[0-9]+'); // Ensure ID is numeric
    });
});
