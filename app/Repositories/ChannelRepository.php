<?php

namespace App\Repositories;

use App\ForumChannel;
use App\Interfaces\ChannelRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class ChannelRepository implements ChannelRepositoryInterface
{
    /**
     * Get's a channel by it's ID
     *
     * @param int
     * @return collection
     */
    public function get($channel_id)
    {
        return ForumChannel::find($channel_id);
    }

    /**
     * Get's all channels.
     *
     * @return mixed
     */
    public function all()
    {
        return ForumChannel::get();
    }


    /**
     * Updates a channel.
     *
     * @param int
     * @param array
     */
    public function update($channel_data)
    {

        $channel = ForumChannel::find($channel_data['id']);

        $channel->name = trim(preg_replace('/\s+/',' ', $channel_data['name']));

        //$channel->slug = str_slug($channel->name);

        $channel->save();
    }

    /**
     * Store a channel.
     *
     * @param obj
     */
    public function store($channel_data)
    {

        $channel = new ForumChannel;

        $channel->shop_id = Cache::get('shop_id');

        $channel->name =  trim(preg_replace('/\s+/',' ', $channel_data['name']));

        $channel->slug =  str_slug($channel->name);

        $channel->save();
    }
}
