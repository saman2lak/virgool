<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Category $category)
    {
        $posts = $category->posts()->with('user', 'categories')->withCount('likes')->simplePaginate(10);

        return response([
            'post' => $posts,
            'category' => $category->loadCount('posts')
        ], 200);
    }

    public function navbar()
    {
        //
        return response([
            'data' => Category::inRandomOrder()->take(5)->get()
        ], 200);
    }
}