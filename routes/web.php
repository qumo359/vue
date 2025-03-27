<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::resource('posts', \App\Http\Controllers\PostController::class)->names('posts'); // Ресурсные маршруты для постов

// Like Routes (маршруты для лайков)
Route::post('/posts/{blogPost}/like', [\App\Http\Controllers\LikeController::class, 'like'])->name('posts.like');
Route::post('/posts/{blogPost}/unlike', [\App\Http\Controllers\LikeController::class, 'unlike'])->name('posts.unlike');

Route::resource('categories', \App\Http\Controllers\BlogCategoryController::class)->only(['index', 'show'])->names('categories'); // Ресурсные маршруты для категорий (только index и show)


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
