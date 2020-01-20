<?php

namespace App\Policies;

use App\User;
use App\ForumThread;
use Illuminate\Auth\Access\HandlesAuthorization;

class ForumThreadPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the forum thread.
     *
     * @param  \App\User  $user
     * @param  \App\ForumThread  $forumThread
     * @return mixed
     */
    public function view(User $user, ForumThread $forumThread)
    {
        
    }

    /**
     * Determine whether the user can create forum threads.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the forum thread.
     *
     * @param  \App\User  $user
     * @param  \App\ForumThread  $forumThread
     * @return mixed
     */
    public function update(User $user, ForumThread $forumThread)
    {
        return $forumThread->user_id === $user->id; // user who created a thread can perform this action
        //return false; // not even a user who created this thread can delete this thread
    }

    /**
     * Determine whether the user can delete the forum thread.
     *
     * @param  \App\User  $user
     * @param  \App\ForumThread  $forumThread
     * @return mixed
     */
    public function delete(User $user, ForumThread $forumThread)
    {
       
        return false;
    }
}
