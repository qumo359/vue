<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PostController extends Controller
{

    public function index(Request $request)
    {
        return Inertia::render('Posts', [
            'posts' => [1,2,3,4,5]
        ]);
    }
}
