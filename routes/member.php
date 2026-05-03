<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'role:member'])
    ->group(function () {

        Route::get('/dashboard', fn () => inertia('Member/Dashboard'))
            ->name('dashboard');

        Route::get('/items', fn () => inertia('Member/Items/Index'))
            ->name('items.index');

        Route::get('/claims', fn () => inertia('Member/Claims/Index'))
            ->name('claims.index');
    });