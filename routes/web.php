<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });




Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('Admin.dashboard');
    })->name('dashboard');

    Route::get('/notifications', function () {
        return view('Admin.notifications');
    })->name('notifications');

    Route::get('/posts', function () {
        return view('Admin.posts');
    })->name('posts');

    Route::get('/users', function () {
        return view('Admin.users');
    })->name('users');
});