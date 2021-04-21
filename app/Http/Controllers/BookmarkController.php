<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    //
    public function store(Request $request, Post $post)
    {
        //
        $post->bookmarks()->sync(
            $request->user()->id,
            false
        );
        return response(['data' => 'bookmard shod.'], 200);
    }

    public function destroy(Request $request, Post $post)
    {
        $post->bookmarks()->detach(
            $request->user()->id
        );
        return response(['data' => 'bookmard pak shod.'], 200);
    }
}