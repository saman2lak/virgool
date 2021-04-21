<?php

namespace App\Policies;

use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function destroy(User $user, Comment $comment)
    {
        return $user->id === $comment->user_id;
    }
}