<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [\App\Http\Controllers\PostController::class, 'showSite']);
Route::get('/blog/{slug}', [\App\Http\Controllers\PostController::class, 'showSite']);

//Route::group(['as' => 'auth.'], function () { // Добавляем 'as' => 'auth.' для группировки имен маршрутов аутентификации
//    // Registration Routes
//    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
//    Route::post('/register', [RegisterController::class, 'register'])->name('register.post'); // Уточняем имя для POST маршрута регистрации
//
//    // Login Routes
//    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
//    Route::post('/login', [LoginController::class, 'login'])->name('login.post'); // Уточняем имя для POST маршрута входа
//
//    // Password Reset Routes
//    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
//    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
//    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
//    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
//
//    // Logout Route (теперь будет POST формой, как и должно быть - в другом месте, например в layouts/app.blade.php)
//    // Route::post('/logout', [LogoutController::class, 'logout'])->name('logout'); // Маршрут Logout обычно определяется в Auth::routes(); или вручную в форме POST
//});



Route::resource('posts', \App\Http\Controllers\PostController::class)->names('post'); // Ресурсные маршруты для постов

// Like Routes (маршруты для лайков)
Route::post('/posts/{blogPost}/like', [\App\Http\Controllers\LikeController::class, 'like'])->name('posts.like');
Route::post('/posts/{blogPost}/unlike', [\App\Http\Controllers\LikeController::class, 'unlike'])->name('posts.unlike');

Route::get('/post/search/', [\App\Http\Controllers\PostController::class, 'search'])->name('post.search'); // Маршрут для поиска постов

Route::post('/posts/{post}/comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('posts.comments.store');


Route::resource('categories', \App\Http\Controllers\BlogCategoryController::class)->only(['index', 'show'])->names('categories')->parameters(['categories' => 'category:slug']);


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
