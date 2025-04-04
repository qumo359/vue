<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PostController extends BaseController
{
    public function showSite()
    {
        return view('web.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Получаем все посты и жадно загружаем связь 'category'
        // $items = BlogPost::with('category')->get();
        $latestPosts = BlogPost::latest('created_at')->take(5)->get();
        $categories = BlogCategory::withCount('posts')->get();
        $items = BlogPost::paginate(10);

        return view('web.posts.index', compact('categories', 'items', 'latestPosts'));
    }
//    public function index(Request $request)
//    {
//        $latestPosts = BlogPost::latest('created_at')->take(5)->with('category')->get();
//        $categories = BlogCategory::withCount('Posts')->get();
//        $items = BlogPost::with('category', 'likes')
//            ->withCount('likes')
//            ->withCount('comments')
//            ->paginate(10)
//            ->through(function ($post) {
//                $hasLiked = false;
//                if (Auth::check()) {
//                    $hasLiked = $post->likes->contains('user_id', Auth::id());
//                }
//
//                return [
//                    'id' => $post->id,
//                    'title' => $post->title,
//                    'excerpt' => $post->excerpt,
//                    'post_image' => $post->post_image,
//                    'category' => $post->category,
//                    'likes_count' => $post->likes_count,
//                    'comments_count' => $post->comments_count,
//                    'has_liked' => $hasLiked,
//                    'created_at' => $post->created_at,
//                    'updated_at' => $post->updated_at,
//                ];
//            });
//
//        return Inertia::render('Posts/Index', [
//            'posts' => $items,
//            'categories' => $categories,
//            'latestPosts' => $latestPosts,
//        ]);
//    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (!$query) {
            return redirect()->route('blog.Posts.index');
        }

        $posts = BlogPost::query()
            ->where('title', 'like', "%{$query}%")
            ->orWhere('excerpt', 'like', "%{$query}%")
            ->latest()
            ->paginate(12);

        $categories = BlogCategory::get();
        $latestPosts = BlogPost::latest()->take(3)->get();

        return view('web.posts.index', [
            'items' => $posts,
            'categories' => $categories,
            'latestPosts' => $latestPosts,
            'query' => $query,
        ]);
    }

//        $Posts = BlogPost::where('title', 'like', "%$request->title%")->orWhere('content_raw', 'like', "%$request->title%")->get();
//dd($Posts);

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
//    public function show(string $id)
//    {
//        $post = BlogPost::findOrFail($id)->with('category', 'comments', 'likes');
//        $categories = BlogCategory::withCount('Posts')->get();
//        $latestPosts = BlogPost::latest('created_at')->take(5)->get();
//        $comments = $post->comments()->whereNull('parent_id')->latest()->get();
//        $prev = BlogPost::where('category_id', $post->category_id)->where('id', '<', $post->id)->first();
//        $next = BlogPost::where('category_id', $post->category_id)->where('id', '>', $post->id)->first();
//
//        $hasLiked = false;
//        if (Auth::check()) {
//            $hasLiked = $post->likes->contains('user_id', Auth::id());
//        }
//
//        return Inertia::render('Posts/Show', [
//            'post' => $post,
//            'comments' => $comments,
//            'categories' => $categories,
//            'latestPosts' => $latestPosts,
//            'prev' => $prev,
//            'next' => $next,
//            'hasLikedPost' => $hasLiked,
//            'postLikesCount' => $post->likes->count(),
//        ]);
//    }
    public function show(string $id)
    {

        $item = BlogPost::find($id);
        $categories = BlogCategory::withCount('posts')->get();
        $latestPosts = BlogPost::latest('created_at')->take(5)->get();
        if (empty($item)) return abort(404);

//        $comments = $item->comments()->with('user')->latest()->get();
        $comments = $item->comments()->whereNull('parent_id')->latest()->get();


        $prev = BlogPost::where('category_id', $item->category_id)->where('id', '<', $item->id)->first();
        $next = BlogPost::where('category_id', $item->category_id)->where('id', '>', $item->id)->first();

        return view('web.posts.show', compact('item', 'comments', 'categories', 'latestPosts', 'prev', 'next'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public
    function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public
    function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public
    function destroy(string $id)
    {
        dd(__METHOD__, $id);
    }
}
