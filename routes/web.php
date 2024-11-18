<?php

use App\Livewire\ReadingProgress;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/reading-progress', ReadingProgress::class)
    ->middleware(['auth', 'verified'])
    ->name('progress');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
