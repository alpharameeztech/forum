<?php

namespace App\Services\Stats;


use App\ForumReply;

class Replies
{

    static public  function count()
    {
        return ForumReply::count();
    }

    static public function recent()
    {

        $reply = ForumReply::latest()->first();

        unset($reply->id);

        return $reply;
    }

}
