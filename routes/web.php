<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/items/{item}', [ItemController::class, 'show'])->name('items.show')->whereNumber('item');
Route::get('/items/{item}/download', [ItemController::class, 'download'])->name('items.download');
Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

// Auth routes (to be implemented in Sprint 2)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('items', ItemController::class)->except(['index', 'show']);
    Route::get('/settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/settings/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.updateAvatar');
});
