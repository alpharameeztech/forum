<?php

namespace App\Repositories;

use App\ForumThread;
use App\Interfaces\ThreadRepositoryInterface;

class ThreadRepository implements ThreadRepositoryInterface
{
    /**
     * Get's a Thread by it's ID
     *
     * @param int
     * @return collection
     */
    public function get($thread_id)
    {
        return ForumThread::find($thread_id);
    }

    /**
     * Get's all Threads.
     *
     * @return mixed
     */
    public function all()
    {
        $query =  ForumThread::with('channel')->with('creator');

        return $this->filter($query);
    }


    /**
     * Updates a Thread.
     *
     * @param int
     * @param array
     */
    public function update($thread_data)
    {

        $thread = ForumThread::find($thread_data['id']);

        $thread->save();
    }

    /**
     * Ban a Thread
     * @param $thread_id
     * @param $boolean
     */
    public function ban($thread_id, $boolean)
    {

        $thread = ForumThread::find($thread_id);

        $thread->is_ban = $boolean;

        $thread->save();

    }

    public function filter($query)
    {

        if( request()->ban === '1' || request()->ban === '0' )  {

            $members = $query->where('is_ban', request('ban'))->get();

            return $members;
        }
        else
        {
            return $query->get();
        }
    }

}
