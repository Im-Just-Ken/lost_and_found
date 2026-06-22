<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::inertia('/', 'Welcome')->name('home');

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = Auth::user();

    if ($user->hasRole(['super-admin', 'moderator'])) {
        return inertia('Admin/Dashboard');
    }

    if ($user->hasRole('member')) {
        return inertia('Member/Dashboard');
    }

    abort(403);
})->name('dashboard');

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
require __DIR__.'/member.php';
require __DIR__.'/settings.php';