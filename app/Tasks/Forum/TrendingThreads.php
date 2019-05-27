<?php

namespace App\Tasks\Forum;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class TrendingThreads {

    protected function cacheKey(){

        return 'trending_threads';
    }


    public function get(){ // get the user's subcribed products

      //$tending_threads = Redis::zrevrange('trending_threads', 0, -1);

      $data = array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 10)); // get the 10 trending threads

        foreach($data as $key => $value)  {
           if($value->name != config('app.name') ){
                unset($data[$key]);
           }
          
        }

        return $data;
     
    }   
 
    public function push($thread){ // get the user's subcribed products

        Redis::zincrby($this->cacheKey(),1, json_encode([
            'name' => config('app.name'),
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
  
    }  

}



