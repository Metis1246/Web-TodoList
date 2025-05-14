<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UploadController;

Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/items/filter', [ItemController::class, 'filter']);

Route::get('/posts/{id}', [ItemController::class, 'show'])->name('posts.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/upload', [UploadController::class, 'upload']);
    Route::get('/items/{id}/edit', [ItemController::class, 'edit']);
    Route::put('/items/{id}', [ItemController::class, 'update'])->name('items.update');
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);
    Route::put('/items/{id}/status', [ItemController::class, 'updateStatus']);
});
