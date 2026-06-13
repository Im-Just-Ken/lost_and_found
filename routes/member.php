<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Items\Controllers\ItemController;
use App\Modules\MissingReport\Member\Controllers\MissingReportController;
use App\Modules\FoundByMe\Member\Controllers\FoundByMeController;
use App\Modules\Items\Member\Controllers\RecoveredItemController;
use App\Modules\Dashboard\Member\Controllers\DashboardController;

Route::middleware(['auth', 'verified', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {

    Route::get('/dashboard', fn () => inertia('Member/Dashboard'))->name('dashboard');

         Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/', [DashboardController::class, '__invoke'])->name('index');
        });

    Route::prefix('/items')->name('items.')->group(function () {
        Route::get('/', [ItemController::class, 'index'])->name('index');
        Route::get('/create', [ItemController::class, 'create'])->name('create');
        Route::post('/', [ItemController::class, 'store'])->name('store');
        Route::get('/{item}/edit', [ItemController::class, 'edit'])->name('edit');
        Route::put('/{item}', [ItemController::class, 'update'])->name('update');
    });

    Route::prefix('recovered-items')->name('recovered-items.')->group(function () {
        Route::get('/', [RecoveredItemController::class, 'index'])->name('index');
        Route::get('/{item}', [RecoveredItemController::class, 'show'])->name('show');
    });

    Route::prefix('community')->name('community.')->group(function () {
     
    Route::prefix('missing-reports')->name('missing-reports.')->group(function () {
        Route::get('/', [MissingReportController::class, 'index'])->name('index');
        Route::get('/{item}', [MissingReportController::class, 'show'])->name('show');
        Route::post('/{item}/found', [MissingReportController::class, 'markAsFound'])->name('found');
    });
    
    Route::prefix('found-by-me')->name('found-by-me.')->group(function () {
        Route::get('/', [FoundByMeController::class, 'index']) ->name('index');
        Route::get('/{item}', [FoundByMeController::class, 'show'])->name('show');
        Route::post('/{item}/found', [FoundByMeController::class, 'markAsFound'])->name('mark-as-found');
    });
    
    });
   

    // NOTIFICATIONS
    Route::get('/notifications', fn () => inertia('Member/Notifications/Index'))
        ->name('notifications.index');
    });

