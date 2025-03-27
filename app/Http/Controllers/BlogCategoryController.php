<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use function PHPUnit\Framework\isInstanceOf;
use function PHPUnit\Framework\isObject;
use function PHPUnit\Framework\isType;

class BlogCategoryController extends Controller
{
    public function show(string $slug, BlogCategory $category = null) // Используем Route Model Binding для получения категории по slug или id
    {
       is_null($category) and $category = BlogCategory::where('slug', $slug)->firstOrFail();

        // Загружаем посты, принадлежащие данной категории, с жадной загрузкой категории
        $posts = $category->posts()->withCount('category')->latest()->paginate(9); // Например, 9 постов на страницу, отсортированные по дате создания
        $categories = (new \App\Models\BlogCategory)->withCount('Posts')->get();
        $latestPosts = BlogPost::latest('created_at')->take(5)->get();

        //return view('blog.categories.show', compact('category', 'posts', 'categories', 'latestPosts')); // Передаем категорию и посты в шаблон

        return inertia('blog.Admin.categories.show', compact('posts', 'categories', 'latestPosts'));
    }
}
