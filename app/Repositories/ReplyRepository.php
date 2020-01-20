<?php

namespace App\Repositories;

use App\ForumReply;
use App\Interfaces\ReplyRepositoryInterface;

class ReplyRepository implements ReplyRepositoryInterface
{
    /**
     * Get's a Reply by it's ID
     *
     * @param int
     * @return collection
     */
    public function get($reply_id)
    {
        return ForumReply::find($reply_id);
    }

    /**
     * Get's all Replies.
     *
     * @return mixed
     */
    public function all()
    {
        $query =  ForumReply::with('thread');

        return $this->filter($query);
    }


    /**
     * Updates a Reply.
     *
     * @param int
     * @param array
     */
    public function update($reply_data)
    {

        $reply = ForumReply::find($reply_data['id']);

        $reply->save();
    }

    /**
     * Ban a Reply
     * @param $Reply_id
     * @param $boolean
     */
    public function ban($reply_id, $boolean)
    {

        $reply = ForumReply::find($reply_id);

        $reply->is_ban = $boolean;

        $reply->save();

    }

    /**
     * When called with any
     * query parameter,
     * the filter will applied
     * @return mixed
     */
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
