<?php

namespace App\Policies;

use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class PostPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Post $post)
    {
        return $user->id === $post->user_id;
    }
}