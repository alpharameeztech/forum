<?php

namespace App\Services\Stats;

use App\ForumThread;

class Threads
{

    static public  function count()
    {
        return ForumThread::count();
    }

    static public function recent()
    {

        $thread = ForumThread::latest()->first();

        unset($thread->id);

        return $thread;
    }

    static public function noReplies()
    {
        return ForumThread::where('replies_count',0)->latest()->get();
    }

}
