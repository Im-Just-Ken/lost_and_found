<?php

use Illuminate\Support\Facades\Route;
use  App\Modules\Permission\Controllers\PermissionController;
use  App\Modules\Role\Controllers\RoleController;

Route::middleware(['auth', 'verified'])->group(function () {

    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');

    // example modules
    Route::inertia('/users', 'Users/Index')->name('users.index');
    Route::inertia('/events', 'Events/Index')->name('events.index');



  Route::prefix('permissions')->name('permissions.')->group(function () {

        Route::get('/', [PermissionController::class, 'index'])
            ->name('index');

        Route::post('/', [PermissionController::class, 'store'])
            ->name('store');

        Route::put('/{permission}', [PermissionController::class, 'update'])
            ->name('update');

        Route::delete('/{permission}', [PermissionController::class, 'destroy'])
            ->name('destroy');

    });

      Route::prefix('roles')->name('roles.')->group(function () {

        Route::get('/', [RoleController::class, 'index'])
            ->name('index');

        Route::post('/', [RoleController::class, 'store'])
            ->name('store');

        Route::put('/{role}', [RoleController::class, 'update'])
            ->name('update');

        Route::delete('/{role}', [RoleController::class, 'destroy'])
            ->name('destroy');

    });

});