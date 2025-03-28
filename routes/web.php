<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('admin', function () {
    return Inertia::render('Admin');
})->middleware(['auth', 'verified'])->name('admin');


Route::resource('posts', \App\Http\Controllers\PostController::class)->names('posts'); // Ресурсные маршруты для постов

// Like Routes (маршруты для лайков)
Route::post('/posts/{blogPost}/like', [\App\Http\Controllers\LikeController::class, 'like'])->name('posts.like');
Route::post('/posts/{blogPost}/unlike', [\App\Http\Controllers\LikeController::class, 'unlike'])->name('posts.unlike');

Route::resource('categories', \App\Http\Controllers\BlogCategoryController::class)->only(['index', 'show'])->names('categories')->parameters(['categories' => 'category:slug']);

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
