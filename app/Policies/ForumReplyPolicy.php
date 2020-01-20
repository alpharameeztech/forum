<?php

namespace App\Policies;

use App\User;
use App\ForumReply;
use Illuminate\Auth\Access\HandlesAuthorization;

class ForumReplyPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the forum reply.
     *
     * @param  \App\User  $user
     * @param  \App\ForumReply  $forumReply
     * @return mixed
     */
    public function view(User $user, ForumReply $forumReply)
    {
        //
    }

    /**
     * Determine whether the user can create forum replies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        
        $lastReply =  $user->fresh()->lastReply;
        
        //if there is no reply of a user
        if(! $lastReply){

            return true;
        }

        return ! $lastReply->wasJustPublished();
    }

    /**
     * Determine whether the user can update the forum reply.
     *
     * @param  \App\User  $user
     * @param  \App\ForumReply  $forumReply
     * @return mixed
     */
    public function update(User $user, ForumReply $forumReply)
    {
      \Log::info('trying to delet');
        return $forumReply->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the forum reply.
     *
     * @param  \App\User  $user
     * @param  \App\ForumReply  $forumReply
     * @return mixed
     */
    public function delete(User $user, ForumReply $forumReply)
    {
        //
    }

    /**
     * Determine whether the user can restore the forum reply.
     *
     * @param  \App\User  $user
     * @param  \App\ForumReply  $forumReply
     * @return mixed
     */
    public function restore(User $user, ForumReply $forumReply)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the forum reply.
     *
     * @param  \App\User  $user
     * @param  \App\ForumReply  $forumReply
     * @return mixed
     */
    public function forceDelete(User $user, ForumReply $forumReply)
    {
        //
    }
}
