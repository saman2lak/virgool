<?php

namespace App\Http\Controllers;

use App\Events\CommentCreatedEvent;
use App\Events\CommentDeleteEvent;
use App\Events\ReplayCreatedEvent;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Notifications\UserCommentNotification;
use App\Notifications\UserReplayNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class CommentController extends Controller
{
    //
    public function store(Request $request, Post $post)
    {
        $request->validate(['content' => 'required']);
        $comment = Comment::create([
            'content' => $request->content,
            'post_id' => $request->post_id,
            'user_id' => $request->user()->id,
        ]);

        event(new CommentCreatedEvent(
            $comment->load(['user', 'post', 'parent', 'replies'])
        ));

        $post->user->notify(new UserCommentNotification($post));

        return response([
            'data' => $comment
        ], 200);
    }

    public function replay(Request $request, Post $post)
    {
        $request->validate(['content' => 'required', 'comment_id' => 'required|exists:comments,id']);
        $replay = Comment::create([
            'content' => $request->content,
            'post_id' => $request->post_id,
            'comment_id' => $request->comment_id,
            'user_id' => $request->user()->id,
        ]);

        event(new ReplayCreatedEvent(
            $replay->load(['user', 'post', 'parent', 'replies'])
        ));

        $post->user->notify(new UserCommentNotification($post));
        Comment::find($request->comment_id)->user->notify(new UserReplayNotification($post));

        return response([
            'data' => $replay
        ], 200);
    }

    public function destroy(Comment $comment)
    {
        $this->authorize('destroy', $comment);
        Event::dispatch(new CommentDeleteEvent($comment));
        $comment->delete();

        return response([
            'data' => 'deleted'
        ], 200);
    }

    public function update(Request $request, Comment $comment)
    {
        $this->authorize('destroy', $comment); // یعنی دیدگاهی که اپدیت میکنی حتما برای این کاربر باشد
        $request->validate(['content' => 'required']);
        $comment->update($request->only("content"));
        return response([
            'data' => $comment->load(['user', 'post', 'parent', 'replies'])
        ], 200);
    }
}