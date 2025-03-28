<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function like(BlogPost $blogPost)
    {

        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'likesCount' => $blogPost->likes()->count(),
                'hasLiked' => false, // Пользователь не залогинен, лайкнуть нельзя
            ]);
        }

        // Проверяем, не лайкнул ли пользователь уже этот пост
        if (!$blogPost->likes()->where('user_id', $user->id)->exists()) {
            $blogPost->likes()->attach($user->id);
        }

        return response()->json([
            'likesCount' => $blogPost->likes()->count(),
            'hasLiked' => true,
        ]);
    }

    public function unlike(BlogPost $blogPost)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'likesCount' => $blogPost->likes()->count(),
                'hasLiked' => false ,
            ]);
        }

        // Открепляем лайк пользователя от поста
        $blogPost->likes()->detach($user->id);

        return response()->json([
            'likesCount' => $blogPost->likes()->count(),
            'hasLiked' => false,
        ]);
    }
}
