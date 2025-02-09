<?php

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PostController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('posts', PostController::class);
    Route::post('posts/{posts}/comments', [CommentController::class, 'store']);
});

Route::get('posts/search', [PostController::class, 'search'])->name('posts.search');
Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');

Route::get('/api/posts/{post}/comments', [CommentController::class, 'index']);
