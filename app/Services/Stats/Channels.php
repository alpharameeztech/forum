<?php

namespace App\Services\Stats;

use App\ForumChannel;
use Illuminate\Support\Facades\Cache;

class Channels
{

    static public  function count()
    {
        return ForumChannel::where('shop_id',Cache::get('shop_id'))
                            ->count();
    }

    static public function totalThreads()
    {

        $channels = ForumChannel::all();

        foreach ($channels as $channel)
        {
            $channel['label'] = $channel->name;

            $channel['value'] = $channel->threads->count();

            unset($channel['id']);

            unset($channel['threads']);

        }
        return $channels;

    }

}
