<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\CommentController;

Route::get('/', [ItemController::class, 'index'])->name('home');
Route::get('/items/filter', [ItemController::class, 'filter']);

Route::get('/posts/{id}', [ItemController::class, 'show'])->name('posts.show');

Route::middleware('auth')->group(function () {
    Route::put('/posts/{id}', [ItemController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{id}', [ItemController::class, 'destroy'])->name('posts.destroy');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/upload', [UploadController::class, 'upload']);
});

Route::post('/items/{item}/comments', [CommentController::class, 'store'])
    ->middleware('auth')
    ->name('items.comments.store');
Route::put('/comments/{comment}', [CommentController::class, 'update'])->middleware('auth');
Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
    ->middleware('auth')
    ->name('comments.destroy');
