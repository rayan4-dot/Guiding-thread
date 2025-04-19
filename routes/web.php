<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CommentController;


Route::prefix('admin')->name('admin.')->middleware('is_admin')->group(function () {
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

Route::middleware(['auth', 'is_user'])->group(function () {

    // Home page
    Route::get('/home', function () {
        return view('user.home');
    })->name('user.home');

    Route::get('/explore', function () {
        return view('user.explore');
    })->name('user.explore');

    // Notifications page
    Route::get('/notifications', function () {
        return view('user.notifications');
    })->name('user.notifications');

    // Posts page
    Route::get('/post', function () {
        return view('user.post');
    })->name('user.post');

    // Profile page
    Route::get('/profile', function () {
        return view('user.profile');
    })->name('user.profile');

    Route::get('/friends', function () {
        return view('user.friends');
    })->name('user.friends');


    Route::get('/settings', function () {
        return view('user.settings');
    })->name('user.settings');

    Route::get('/update-password', function () {
        return view('user.update-password');
    })->name('user.update-password');
    Route::post('/posts/{post}/comments', [CommentController::class, 'storeComment'])->name('posts.comments.store');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::delete('/profile/picture', [ProfileController::class, 'removePicture'])->name('user.profile.remove-picture');
    Route::delete('/profile/cover', [ProfileController::class, 'removeCoverPhoto'])->name('user.profile.remove-cover');
    Route::get('/home', [HomeController::class, 'index'])->name('user.home');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.destroy');
});

Route::get('/profile/{username}', [ProfileController::class, 'showPublicProfile'])->name('user.public-profile');
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');




Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');

Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
