<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/users', function () {
    return view('users');
})->name('users');

Route::get('/post', function () {
    return view('post');
})->name('post');

Route::get('/notifications', function () {
    return view('notifications');
})->name('notifications');