<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminPostController;
use App\Http\Controllers\PostController;

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PublicProfileController;
use App\Http\Controllers\Admin\DashboardController;


// Admin Routes
Route::prefix('admin')->name('admin.')->middleware('is_admin')->group(function () {
    Route::get('/notifications', function () {
        return view('Admin.notifications');
    })->name('notifications');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // User Management
    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/users/{user}/details', [UserController::class, 'details'])->name('users.details');
    Route::patch('/users/{user}/suspend', [UserController::class, 'suspend'])->name('users.suspend');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Post Management
    Route::get('/posts', [AdminPostController::class, 'index'])->name('posts');
    Route::get('/posts/{post}/details', [AdminPostController::class, 'details'])->name('posts.details'); // Updated to AdminPostController
    Route::delete('/posts/{post}', [AdminPostController::class, 'destroy'])->name('posts.destroy');

});


// Authenticated User Routes
Route::middleware(['auth', 'is_user'])->group(function () {
    Route::post('/user/update-password', [AuthController::class, 'updatePassword'])->name('user.update-password');
    Route::delete('/user/delete-account', [AuthController::class, 'deleteAccount'])->name('user.delete-account');

    Route::get('/home', [HomeController::class, 'index'])->name('user.home');
    Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
    Route::get('/notifications', function () {
        return view('user.notifications');
    })->name('user.notifications');
    Route::get('/post', function () {
        return view('user.post');
    })->name('user.post');
    Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('user.profile.update');
    Route::delete('/profile/picture', [ProfileController::class, 'removePicture'])->name('user.profile.remove-picture');
    Route::delete('/profile/cover', [ProfileController::class, 'removeCoverPhoto'])->name('user.profile.remove-cover');
    Route::get('/friends', function () {
        return view('user.friends');
    })->name('user.friends');
    Route::get('/suspended', function () {
        return view('auth.suspended');
    })->name('suspended');
    Route::get('/settings', function () {
        return view('user.settings');
    })->name('user.settings');
    Route::get('/search', [SearchController::class, 'index'])->name('search.index');
    Route::post('/posts/{post}/comments', [CommentController::class, 'storeComment'])->name('posts.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::post('/follow/{user}', [FollowController::class, 'follow'])->name('follow');
    Route::delete('/unfollow/{user}', [FollowController::class, 'unfollow'])->name('unfollow');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.destroy');
});

// Public Routes
Route::get('/profile/{username}', [PublicProfileController::class, 'show'])->name('public-profile.show');
Route::get('/post/{id}', [PostController::class, 'show'])->name('post.show');

// Auth Routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');