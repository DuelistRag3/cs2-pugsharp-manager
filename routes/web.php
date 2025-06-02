<?php

use App\Livewire\Landing;
use Illuminate\Support\Facades\Route;

Route::get('/', Landing::class)
    ->name('landing');
