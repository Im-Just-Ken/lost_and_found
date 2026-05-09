<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Items\Controllers\ItemController;

Route::middleware(['auth', 'verified', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {

      Route::get('/dashboard', fn () => inertia('Member/Dashboard'))
            ->name('dashboard');



    Route::prefix('/items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::get('/create', [ItemController::class, 'create'])->name('create');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [ItemController::class, 'update'])->name('update');
    });


    // CLAIMS
    Route::get('/claims', fn () => inertia('Member/Claims/Index'))
        ->name('claims.index');

    // REPORTED ITEMS
    Route::get('/reported-items', fn () => inertia('Member/ReportedItems/Index'))
        ->name('reported-items.index');

    // NOTIFICATIONS
    Route::get('/notifications', fn () => inertia('Member/Notifications/Index'))
        ->name('notifications.index');
    });

