<?php

use Illuminate\Support\Facades\Route;
use  App\Modules\Permission\Controllers\PermissionController;
use  App\Modules\Role\Controllers\RoleController;
use  App\Modules\AccessGroup\Controllers\AccessGroupController;
use  App\Modules\RolePermission\Controllers\RolePermissionSyncController;

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

        Route::get('/{role}/edit', [RoleController::class, 'edit'])
            ->name('edit');

        Route::post('/', [RoleController::class, 'store'])
            ->name('store');

        Route::put('/{role}', [RoleController::class, 'update'])
            ->name('update');

        Route::delete('/{role}', [RoleController::class, 'destroy'])
            ->name('destroy');

    });


      Route::prefix('roles-permissions')->name('roles-permissions.')->group(function () {

        Route::post('/sync', [RolePermissionSyncController::class, 'store']);

    });

          Route::prefix('access-groups')->name('access_groups.')->group(function () {

        Route::get('/', [AccessGroupController::class, 'index'])
            ->name('index');

           Route::get('/{access_group}/edit', [AccessGroupController::class, 'edit'])
            ->name('edit');

        Route::post('/', [AccessGroupController::class, 'store'])
            ->name('store');

        Route::put('/{access_group}', [AccessGroupController::class, 'update'])
            ->name('update');

        Route::delete('/{access_group}', [AccessGroupController::class, 'destroy'])
            ->name('destroy');

    });

});