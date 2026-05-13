<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Items\Controllers\ItemController;
use App\Modules\ReportedItem\Member\Controllers\ReportedItemController;
use App\Modules\UserFoundItem\Member\Controllers\UserFoundItemController;

Route::middleware(['auth', 'verified', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {

      Route::get('/dashboard', fn () => inertia('Member/Dashboard'))
            ->name('dashboard');


    //ITEMS
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
    Route::prefix('/reported-items')->name('reported-items.')->group(function () {
        Route::get('/', [ReportedItemController::class, 'index'])->name('index');
        Route::get('/{item}', [ReportedItemController::class, 'show'])->name('show');
        Route::post('/{item}/found', [ReportedItemController::class, 'markAsFound'])->name('mark-as-found');
        });

    Route::prefix('/items-i-found')->name('items-i-found.')->group(function () {
        Route::get('/', [UserFoundItemController::class, 'index'])->name('index');
        Route::get('/{item}', [UserFoundItemController::class, 'show'])->name('show');
        Route::post('/{item}/found', [UserFoundItemController::class, 'markAsFound'])->name('mark-as-found');
        });
    
    // NOTIFICATIONS
    Route::get('/notifications', fn () => inertia('Member/Notifications/Index'))
        ->name('notifications.index');
    });

