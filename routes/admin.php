<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Blog\Admin\ImageUploadController;

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


//>Админка блога

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'App\Http\Controllers\Admin'], function () {
    Route::resource('categories', 'CategoryController')->names('categories');
    Route::get('/users', 'UsersController@index')->name('users.index');
    Route::get('/users/{user}/edit', 'UsersController@edit')->name('users.edit');
    Route::put('/users/{user}', 'UsersController@update')->name('users.update');
    Route::delete('/users/{user}', 'UsersController@destroy')->name('users.destroy');
    Route::resource('posts', 'PostController')->names('posts');
    // Route::put('/blog/posts/{post}', 'PostController@update')->name('blog.update'); // Можно удалить этот дубликат
});
Route::get('admin', function () {
    return Inertia::render('Admin');
})->middleware(['auth', 'verified'])->name('admin');

