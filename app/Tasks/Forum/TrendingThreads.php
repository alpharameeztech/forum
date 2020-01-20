<?php

namespace App\Tasks\Forum;

use App\ForumThread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class TrendingThreads {

    protected function cacheKey(){

        return 'trending_threads';
    }

    public function __invoke()
    {
        return $this->get();
    }

    public function get(){ // get the user's subcribed products

      //$tending_threads = Redis::zrevrange('trending_threads', 0, -1);

      $data = array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 9)); // get the 10 trending threads

        foreach($data as $key => $value)  {

               if( empty($value->shop_id) || !empty( $value->shop_id) && $value->shop_id != Cache::get('shop_id')  )
               {

                   unset($data[$key]);
               }else
                   {

                       $thread  = ForumThread::where('title', $value->title)->first();

                       if(!empty($thread))
                       {

                           $data[$key]->visits = $thread->visits()->count();

                           $data[$key]->totalReplies = $thread->replies_count;
                       }
               }
        }

        $data = collect($data);

        $sorted = $data->sortByDesc('visits'); // sorty by most replies

        return $sorted->values()->all();

        return $sorted;

    }

    public function push($thread){

        Redis::zincrby($this->cacheKey(),1, json_encode([
            'shop_id' => Cache::get('shop_id'),
            'id' => $thread->id,
            'name' => config('app.name'),
            'title' => $thread->title,
            'path' => $thread->path()
        ]));

    }

}



