<?php

namespace App\Policies;

use App\Models\Draft;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;

class DraftPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function show(User $user, Draft $draft)
    {
        return $user->id === $draft->user_id;
    }
}