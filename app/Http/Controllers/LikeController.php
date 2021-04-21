<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Notifications\UserLikeNotification;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    //
    public function store(Request $request, Post $post)
    {
        $post->likes()->sync(
            $request->user()->id,
            false
        );

        $post->user->notify(new UserLikeNotification($post));

        return response(['data' => 'like shod'], 200);
    }

    public function destroy(Request $request, Post $post)
    {
        $post->likes()->detach($request->user()->id);
        return response(['data' => 'like hazf shod'], 200);
    }
}